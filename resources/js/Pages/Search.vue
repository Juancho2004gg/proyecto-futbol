<script setup>
import UserListItem from "@/Components/app/UserListItem.vue";
import PostList from "@/Components/app/PostList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    search: String,
    users: Array,
    posts: Object
})

</script>

<template>
    <AuthenticatedLayout>
        <div class="p-4">
            <div v-if="!search.startsWith('#')" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="shadow bg-white p-3 rounded mb-3">
                    <h2 class="text-lg font-bold">Usuarios</h2>
                    <div class="grid-cols-2">
                        <UserListItem v-if="users.length" v-for="user of users" :user="user"/>
                        <div v-else class="py-8 text-center text-gray-500">
                            No se encontraron usuarios.
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-bold">Posts</h2>
                <PostList v-if="posts.data.length" :posts="posts.data" class="flex-1"/>
                <div v-else class="py-8 text-center text-gray-500">
                    No se encontraron publicaciones.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
