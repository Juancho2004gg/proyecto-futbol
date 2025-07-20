<script setup>
import {Link} from '@inertiajs/vue3'

defineProps({
    user: Object
})

</script>

<template>
    <div class="bg-white dark:bg-slate-900 dark:text-gray-100 transition-all border-2 border-transparent hover:border-indigo-500">
        <div class="flex items-center gap-2 py-2 px-2">
            <Link :href="route('profile', user.username)">
                <img :src="user.avatar_url" class="w-[32px] rounded-full"/>
            </Link>
            <div class="flex justify-between flex-1">
                <Link :href="route('profile', user.username)">
                    <h3 class="font-black hover:underline">{{ user.name }}</h3>
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
