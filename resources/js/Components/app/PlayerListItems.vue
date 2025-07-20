<script setup>
import TextInput from "@/Components/TextInput.vue";
import UserListItem from "@/Components/app/UserListItem.vue";
import {ref, computed} from "vue"; // Import 'computed'

const searchKeyword = ref('')

const props = defineProps({
    users: {
        type: Array,
        default: () => []
    }
})

const filteredPlayers = computed(() => {
    const players = props.users.filter(user => user.role === 'futbolista');

    if (searchKeyword.value) {
        const lowerCaseKeyword = searchKeyword.value.toLowerCase();
        return players.filter(user =>
            user.name.toLowerCase().includes(lowerCaseKeyword) ||
            user.email.toLowerCase().includes(lowerCaseKeyword)
        );
    }
    console.log(props.users);
    console.log(players);
    return players;
});

</script>

<template>
        <TextInput v-model="searchKeyword" placeholder="Buscar futbolista..." class="w-full mt-3"/>
        <div class="mt-3 h-[200px] lg:flex-1 overflow-auto">
            <div v-if="filteredPlayers.length === 0" class="text-gray-400 text-center p-3">
                No hay futbolistas para mostrar.
            </div>
            <div v-else class="space-y-2">
                <UserListItem v-for="user of filteredPlayers"
                              :user="user"
                              :key="user.id"
                              class="rounded-lg p-2 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200"/>
            </div>
        </div>
</template>

<style scoped>
</style>
