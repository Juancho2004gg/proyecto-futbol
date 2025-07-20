<script setup>
import {Head, Link} from '@inertiajs/vue3';
import FollowingList from "@/Components/app/FollowingList.vue";
import CreatePost from "@/Components/app/CreatePost.vue";
import PostList from "@/Components/app/PostList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PlayerListAside from "@/Components/app/PlayerListAside.vue";

defineProps({
    posts: Object,
    followings: Array,
    users: Array
});
</script>

<template>
    <Head title="Football Tracker"/>

    <AuthenticatedLayout>
        <template #leftSidebar>
            <PlayerListAside :users="users" />
        </template>

        <div class="h-full overflow-hidden flex flex-col">
            <CreatePost/>
            <PostList :posts="posts.data" class="flex-1"/>
        </div>

        <template #rightSidebar>
            <FollowingList :users="followings"/>
        </template>
    </AuthenticatedLayout>
</template>

<style>
</style>
