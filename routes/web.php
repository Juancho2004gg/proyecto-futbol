<?php
use App\Http\Controllers\PlayerProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/u/{user:username}', [ProfileController::class, 'index'])
    ->name('profile');


Route::middleware('auth')->group(function () {


//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-images', [ProfileController::class, 'updateImage'])
        ->name('profile.updateImages');

    Route::post('/user/follow/{user}', [UserController::class, 'follow'])->name('user.follow');

    // Posts
    Route::prefix('/post')->group(function () {
        Route::get('/{post}', [PostController::class, 'view'])
            ->name('post.view');

        Route::post('', [PostController::class, 'store'])
            ->name('post.create');

        Route::put('/{post}', [PostController::class, 'update'])
            ->name('post.update');

        Route::delete('/{post}', [PostController::class, 'destroy'])
            ->name('post.destroy');

        Route::get('/download/{attachment}', [PostController::class, 'downloadAttachment'])
            ->name('post.download');

        Route::post('/{post}/reaction', [PostController::class, 'postReaction'])
            ->name('post.reaction');

        Route::post('/{post}/comment', [PostController::class, 'createComment'])
            ->name('post.comment.create');

        Route::post('/ai-post', [PostController::class, 'aiPostContent'])
            ->name('post.aiContent');

        Route::post('/fetch-url-preview', [PostController::class, 'fetchUrlPreview'])
            ->name('post.fetchUrlPreview');

        Route::post('/{post}/pin', [PostController::class, 'pinUnpin'])
            ->name('post.pinUnpin');
    });

    // Comments
    Route::delete('/comment/{comment}', [PostController::class, 'deleteComment'])
        ->name('comment.delete');

    Route::put('/comment/{comment}', [PostController::class, 'updateComment'])
        ->name('comment.update');

    Route::post('/comment/{comment}/reaction', [PostController::class, 'commentReaction'])
        ->name('comment.reaction');

    Route::get('/search/{search?}', [SearchController::class, 'search'])
        ->name('search');

    // Player Profile
    Route::get('/player/profile/create', [PlayerProfileController::class, 'create'])->name('player.profile.create');
    Route::post('/player/profile/store', [PlayerProfileController::class, 'store'])->name('player.profile.store');
});

require __DIR__ . '/auth.php';
