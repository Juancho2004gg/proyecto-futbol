<?php

namespace App\Http\Controllers;

use App\Http\Enums\ReactionEnum;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\Reaction;
use App\Models\User;
use App\Notifications\CommentCreated;
use App\Notifications\CommentDeleted;
use App\Notifications\PostCreated;
use App\Notifications\PostDeleted;
use App\Notifications\ReactionAddedOnComment;
use App\Notifications\ReactionAddedOnPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class PostController extends Controller
{

    public function view(Request $request, Post $post)
    {
        // REMOVED: Group permission check - posts are now publicly viewable
        $post->loadCount('reactions');
        $post->load([
            'comments' => function ($query) {
                $query->withCount('reactions'); // SELECT * FROM comments WHERE post_id IN (1, 2, 3...)
                // SELECT COUNT(*) from reactions
            },
        ]);

        return inertia('Post/View', [
            'post' => new PostResource($post)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        DB::beginTransaction();
        $allFilePaths = [];
        try {
            $post = Post::create($data);

            /** @var \Illuminate\Http\UploadedFile[] $files */
            $files = $data['attachments'] ?? [];
            foreach ($files as $file) {
                $path = Storage::disk('cloudinary')->put('attachments/' . $post->id, $file);
                $url = Storage::disk('cloudinary')->url($path);
                $allFilePaths[] = $path;
                PostAttachment::create([
                    'post_id' => $post->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $url,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'created_by' => $user->id
                ]);
            }

            DB::commit();

            // REMOVED: Group notification logic - only send to followers now
            $followers = $user->followers;
            Notification::send($followers, new PostCreated($post, $user, null));

        } catch (\Exception $e) {
            foreach ($allFilePaths as $path) {
                Storage::disk('public')->delete($path);
            }
            DB::rollBack();
            throw $e;
        }

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $user = $request->user();

        DB::beginTransaction();
        $allFilePaths = [];
        try {
            $data = $request->validated();
            $post->update($data);

            $deleted_ids = $data['deleted_file_ids'] ?? []; // 1, 2, 3, 4

            $attachments = PostAttachment::query()
                ->where('post_id', $post->id)
                ->whereIn('id', $deleted_ids)
                ->get();

            foreach ($attachments as $attachment) {
                $attachment->delete();
            }

            /** @var \Illuminate\Http\UploadedFile[] $files */
            $files = $data['attachments'] ?? [];
            foreach ($files as $file) {
                $path = Storage::disk('cloudinary')->put('attachments/' . $post->id, $file);
                $url = Storage::disk('cloudinary')->url($path);
                $allFilePaths[] = $path;
                PostAttachment::create([
                    'post_id' => $post->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $url,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'created_by' => $user->id
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            foreach ($allFilePaths as $path) {
                Storage::disk('public')->delete($path);
            }
            DB::rollBack();
            throw $e;
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $id = Auth::id();

        // REMOVED: Group admin permission check - only owner can delete now
        if ($post->isOwner($id)) {
            $post->delete();
            return back();
        }

        return response("You don't have permission to delete this post", 403);
    }

    public function downloadAttachment(PostAttachment $attachment)
    {
        // TODO check if user has permission to download that attachment

        return response()->download(Storage::disk('public')->path($attachment->path), $attachment->name);
    }

    public function postReaction(Request $request, Post $post)
    {
        $data = $request->validate([
            'reaction' => [Rule::enum(ReactionEnum::class)]
        ]);

        $userId = Auth::id();
        $reaction = Reaction::where('user_id', $userId)
            ->where('object_id', $post->id)
            ->where('object_type', Post::class)
            ->first();

        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;
            Reaction::create([
                'object_id' => $post->id,
                'object_type' => Post::class,
                'user_id' => $userId,
                'type' => $data['reaction']
            ]);

            if (!$post->isOwner($userId)) {
                $user = User::where('id', $userId)->first();
                $post->user->notify(new ReactionAddedOnPost($post, $user));
            }
        }

        $reactions = Reaction::where('object_id', $post->id)->where('object_type', Post::class)->count();

        return response([
            'num_of_reactions' => $reactions,
            'current_user_has_reaction' => $hasReaction
        ]);
    }

    public function createComment(Request $request, Post $post)
    {
        $data = $request->validate([
            'comment' => ['required'],
            'parent_id' => ['nullable', 'exists:comments,id']
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'comment' => nl2br($data['comment']),
            'user_id' => Auth::id(),
            'parent_id' => $data['parent_id'] ?: null
        ]);

        $post = $comment->post;
        $post->user->notify(new CommentCreated($comment, $post));

        return response(new CommentResource($comment), 201);
    }

    public function deleteComment(Comment $comment)
    {
        $post = $comment->post;
        $id = Auth::id();
        if ($comment->isOwner($id) || $post->isOwner($id)) {

            $allComments = Comment::getAllChildrenComments($comment);
            $deletedCommentCount = count($allComments);

            $comment->delete();

            if (!$comment->isOwner($id)) {
                $comment->user->notify(new CommentDeleted($comment, $post));
            }


            return response(['deleted' => $deletedCommentCount], 200);
        }

        return response("You don't have permission to delete this comment.", 403);


    }

    public function updateComment(UpdateCommentRequest $request, Comment $comment)
    {
        $data = $request->validated();

        $comment->update([
            'comment' => nl2br($data['comment'])
        ]);

        return new CommentResource($comment);
    }

    public function commentReaction(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'reaction' => [Rule::enum(ReactionEnum::class)]
        ]);

        $userId = Auth::id();
        $reaction = Reaction::where('user_id', $userId)
            ->where('object_id', $comment->id)
            ->where('object_type', Comment::class)
            ->first();

        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;
            Reaction::create([
                'object_id' => $comment->id,
                'object_type' => Comment::class,
                'user_id' => $userId,
                'type' => $data['reaction']
            ]);

            if (!$comment->isOwner($userId)) {
                $user = User::where('id', $userId)->first();
                $comment->user->notify(new ReactionAddedOnComment($comment->post, $comment, $user));
            }
        }

        $reactions = Reaction::where('object_id', $comment->id)->where('object_type', Comment::class)->count();

        return response([
            'num_of_reactions' => $reactions,
            'current_user_has_reaction' => $hasReaction
        ]);
    }



    public function fetchUrlPreview(Request $request)
    {
        $data = $request->validate([
            'url' => 'url'
        ]);
        $url = $data['url'];

        $html = file_get_contents($url);

        $dom = new \DOMDocument();

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);

        // Load HTML content into the DOMDocument
        $dom->loadHTML($html);

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(false);

        $ogTags = [];
        $metaTags = $dom->getElementsByTagName('meta');
        foreach ($metaTags as $tag) {
            $property = $tag->getAttribute('property');
            if (str_starts_with($property, 'og:')) {
                $ogTags[$property] = $tag->getAttribute('content');
            }
        }

        return $ogTags;
    }

    public function pinUnpin($postId)
    {
        $user = auth()->user();

        // Si el post ya está anclado, lo desancla; si no, lo ancla
        if ($user->pinned_post_id == $postId) {
            $user->pinned_post_id = null;
        } else {
            $user->pinned_post_id = $postId;
        }
        $user->save();

        return back()->with('success', 'Post anclado/desanclado correctamente.');
    }
}
