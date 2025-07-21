<script setup>
import {computed, ref} from 'vue'
import {XMarkIcon, CheckCircleIcon, CameraIcon} from '@heroicons/vue/24/solid'
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import {usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from '@inertiajs/vue3'
import DangerButton from "@/Components/DangerButton.vue";
import CreatePost from "@/Components/app/CreatePost.vue";
import PostList from "@/Components/app/PostList.vue";
import UserListItem from "@/Components/app/UserListItem.vue";
import TextInput from "@/Components/TextInput.vue";
import PostAttachments from "@/Components/app/PostAttachments.vue";
import TabPhotos from "@/Pages/Profile/TabPhotos.vue";

const imagesForm = useForm({
    avatar: null,
    cover: null,
})

const showNotification = ref(true)
const coverImageSrc = ref('')
const avatarImageSrc = ref('')
const searchFollowersKeyword = ref('')
const searchFollowingsKeyword = ref('')
const authUser = usePage().props.auth.user;

const props = defineProps({
    errors: Object,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    success: {
        type: String,
    },
    isCurrentUserFollower: Boolean,
    followerCount: Number,
    user: {
        type: Object
    },
    player_profiles: {
        type: Object,
    },
    posts: Object,
    followers: Array,
    followings: Array,
    photos: Array
});
console.log('props.user.role:', props.user)
console.log('props.player_profiles:', props.player_profiles)

const isMyProfile = computed(() => authUser && props.user && authUser.id === props.user.id)

function onCoverChange(event) {
    imagesForm.cover = event.target.files[0]
    if (imagesForm.cover && isMyProfile) {
        const reader = new FileReader()
        reader.onload = () => {
            coverImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.cover)
    }
}

function onAvatarChange(event) {
    imagesForm.avatar = event.target.files[0]
    if (imagesForm.avatar && isMyProfile) {
        const reader = new FileReader()
        reader.onload = () => {
            avatarImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.avatar)
    }
}

function resetCoverImage() {
    imagesForm.cover = null;
    coverImageSrc.value = null
}

function resetAvatarImage() {
    imagesForm.avatar = null;
    avatarImageSrc.value = null
}

function submitCoverImage() {
    imagesForm.post(route('profile.updateImages'), {
        preserveScroll: true,
        onSuccess: (user) => {
            showNotification.value = true
            resetCoverImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    })
}

function submitAvatarImage() {
    imagesForm.post(route('profile.updateImages'), {
        preserveScroll: true,
        onSuccess: (user) => {
            showNotification.value = true
            resetAvatarImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    })
}

function followUser() {
    const form = useForm({
        follow: !props.isCurrentUserFollower
    })

    form.post(route('user.follow', props.user.id), {
        preserveScroll: true
    })
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-[768px] mx-auto h-full overflow-auto">
            <div class="px-4">
                <div
                    v-show="showNotification && success"
                    class="my-2 py-2 px-3 font-medium text-sm bg-emerald-500 text-white"
                >
                    {{ success }}
                </div>
                <div
                    v-if="errors.cover"
                    class="my-2 py-2 px-3 font-medium text-sm bg-red-400 text-white"
                >
                    {{ errors.cover }}
                </div>

                <div class="group relative bg-white dark:bg-slate-950 dark:text-gray-100">
                    <img :src="coverImageSrc || user.cover_url || '/img/default_cover.jpg'"
                         class="w-full h-[200px] object-cover">
                    <div class="absolute top-2 right-2 ">
                        <button
                            v-if="!coverImageSrc"
                            class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center opacity-0 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-3 h-3 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                            </svg>

                            Actualizar foto
                            <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                   @change="onCoverChange"/>
                        </button>
                        <div v-else class="flex gap-2 bg-white p-2 opacity-0 group-hover:opacity-100">
                            <button
                                @click="resetCoverImage"
                                class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center">
                                <XMarkIcon class="h-3 w-3 mr-2"/>
                                Cancelar
                            </button>
                            <button
                                @click="submitCoverImage"
                                class="bg-gray-800 hover:bg-gray-900 text-gray-100 py-1 px-2 text-xs flex items-center">
                                <CheckCircleIcon class="h-3 w-3 mr-2"/>
                                Subir
                            </button>
                        </div>
                    </div>

                    <div class="flex">
                        <div
                            class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full">
                            <img :src="avatarImageSrc || user.avatar_url || '/img/default_avatar.webp'"
                                 class="w-full h-full object-cover rounded-full">
                            <button
                                v-if="!avatarImageSrc"
                                class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/avatar:opacity-100">
                                <CameraIcon class="w-8 h-8"/>

                                <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                       @change="onAvatarChange"/>
                            </button>
                            <div v-else class="absolute top-1 right-0 flex flex-col gap-2">
                                <button
                                    @click="resetAvatarImage"
                                    class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                    <XMarkIcon class="h-5 w-5"/>
                                </button>
                                <button
                                    @click="submitAvatarImage"
                                    class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                                    <CheckCircleIcon class="h-5 w-5"/>
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between items-center flex-1 p-4">
                            <div>
                                <h2 class="font-bold text-lg">{{ user.name }}</h2>
                                <p class="text-xs text-gray-500">{{ followerCount }} seguidor(es)</p>
                            </div>

                            <div v-if="!isMyProfile">
                                <PrimaryButton v-if="!isCurrentUserFollower" @click="followUser">
                                    Seguir Usuario
                                </PrimaryButton>
                                <DangerButton v-else @click="followUser">
                                    Dejar de seguir
                                </DangerButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t m-4 mt-0">
                <TabGroup>
                    <TabList class="flex bg-white dark:bg-slate-950 dark:text-white">
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Posts" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Seguidores" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Seguidos" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Fotos" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Mi Perfil" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem
                                text="Trayectoria deportiva"
                                :selected="selected"
                                :class="{'opacity-50 pointer-events-none': props.user.role !== 'futbolista'}"
                            />
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel>
                            <template v-if="posts">
                                <CreatePost />
                                <PostList :posts="posts.data" class="flex-1"/>
                            </template>
                            <div v-else class="py-8 text-center dark:text-gray-100">
                                No tienes permisos para ver estas publicaciones.
                            </div>
                        </TabPanel>
                        <TabPanel>
                            <div class="mb-3">
                                <TextInput :model-value="searchFollowersKeyword" placeholder="Escriba para buscar"
                                           class="w-full"/>
                            </div>
                            <div v-if="followers.length" class="grid grid-cols-2 gap-3">
                                <UserListItem v-for="user of followers"
                                              :user="user"
                                              :key="user.id"
                                              class="shadow rounded-lg"/>
                            </div>
                            <div v-else class="text-center py-8">
                                El usuario no tiene seguidores.
                            </div>
                        </TabPanel>
                        <TabPanel>
                            <div class="mb-3">
                                <TextInput :model-value="searchFollowingsKeyword" placeholder="Escriba para buscar"
                                           class="w-full"/>
                            </div>
                            <div v-if="followings.length" class="grid grid-cols-2 gap-3">
                                <UserListItem v-for="user of followings"
                                              :user="user"
                                              :key="user.id"
                                              class="shadow rounded-lg"/>
                            </div>
                            <div v-else class="text-center py-8">
                                El usuario no sigue a nadie.
                            </div>
                        </TabPanel>
                        <TabPanel>
                            <TabPhotos :photos="photos" />
                        </TabPanel>
                        <TabPanel>
                            <template v-if="isMyProfile">
                                <Edit :must-verify-email="mustVerifyEmail" :status="status"/>
                            </template>
                        </TabPanel>
                        <TabPanel>
                            <template v-if="props.user.role === 'futbolista'">
                                <div class="p-4 bg-white dark:bg-gray-900 dark:text-gray-100 rounded shadow">
                                    <h2 class="text-lg font-bold mb-4">Trayectoria Deportiva</h2>
                                    <div class="mb-2"><span class="font-semibold">Edad:</span> {{ props.player_profiles.age || 'No especificado' }}</div>
                                    <div class="mb-2"><span class="font-semibold">Posición(es):</span> {{ props.player_profiles.positions || 'No especificado' }}</div>
                                    <div class="mb-2"><span class="font-semibold">Pierna hábil:</span> {{ props.player_profiles.preferred_foot || 'No especificado' }}</div>
                                    <div class="mb-2"><span class="font-semibold">Club(es) actual(es):</span> {{ props.player_profiles.current_clubs || 'No especificado' }}</div>
                                    <div class="mb-2"><span class="font-semibold">Club(es) anteriores:</span> {{ props.player_profiles.previous_clubs || 'No especificado' }}</div>
                                    <div class="mb-2"><span class="font-semibold">Ligas y temporadas:</span> {{ props.player_profiles.leagues_seasons || 'No especificado' }}</div>
                                    <div class="mb-2"><span class="font-semibold">Estadísticas relevantes:</span> {{ props.player_profiles.stats || 'No especificado' }}</div>
                                    <div class="mb-2"><span class="font-semibold">Logros y distinciones:</span> {{ props.player_profiles.achievements || 'No especificado' }}</div>
                                    <div class="mb-2"><span class="font-semibold">Sobre mí:</span> {{ props.player_profiles.about || 'No especificado' }}</div>
                                </div>
                            </template>
                            <template v-else>
                                <div class="p-4 text-center text-gray-400">
                                    Este usuario no tiene trayectoria deportiva.
                                </div>
                            </template>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>

