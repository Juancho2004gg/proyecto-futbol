<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    age: '',
    positions: '',
    preferred_foot: '',
    current_clubs: '',
    previous_clubs: '',
    leagues_seasons: '',
    stats: '',
    achievements: '',
    about: '',
});

const submit = () => {
    form.post(route('player.profile.store'));
};
</script>

<template>
    <div class="w-full sm:max-w-md min-h-screen overflow-auto mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md rounded-lg mx-auto">
        <h1 class="text-xl font-bold mb-4 text-center text-gray-900 dark:text-gray-100">Información Deportiva</h1>
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="age" value="Edad" />
                <TextInput id="age" v-model="form.age" class="mt-1 block w-full" />
                <InputError :message="form.errors.age" />
            </div>

            <div class="mt-4">
                <InputLabel for="positions" value="Posición(es) de Juego" />
                <select id="positions" v-model="form.positions" class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-900 dark:text-white">
                    <option value="">Selecciona una opción</option>
                    <option value="portero">Portero</option>
                    <option value="defensa_central">Defensa Central</option>
                    <option value="lateral">Lateral</option>
                    <option value="libero">Libero</option>
                    <option value="carrilero">Carrilero</option>
                    <option value="mediocentro_defensivo">Mediocentro Defensivo</option>
                    <option value="mediocentro">Mediocentro</option>
                    <option value="mediocentro_ofensivo">Mediocentro Ofensivo</option>
                    <option value="interior">Interior</option>
                    <option value="extremo">Extremo</option>
                    <option value="delantero_centro">Delantero Centro</option>
                    <option value="segundo_delantero">Segundo Delantero</option>
                    <option value="falso_nueve">Falso Nueve</option>
                </select>
                <InputError :message="form.errors.positions" />
            </div>

            <div class="mt-4">
                <InputLabel for="preferred_foot" value="Pierna Hábil" />
                <select id="preferred_foot" v-model="form.preferred_foot" class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-900 dark:text-white">
                    <option value="">Selecciona una opción</option>
                    <option value="derecha">Derecha</option>
                    <option value="izquierda">Izquierda</option>
                    <option value="ambas">Ambas</option>
                </select>
                <InputError :message="form.errors.preferred_foot" />
            </div>



            <div class="mt-4">
                <InputLabel for="current_clubs" value="Club(es) Actual(es)" />
                <TextInput id="current_clubs" v-model="form.current_clubs" class="mt-1 block w-full" />
                <InputError :message="form.errors.current_clubs" />
            </div>
            <div class="mt-4">
                <InputLabel for="previous_clubs" value="Club(es) Anteriores" />
                <TextInput id="previous_clubs" v-model="form.previous_clubs" class="mt-1 block w-full" />
                <InputError :message="form.errors.previous_clubs" />
            </div>
            <div class="mt-4">
                <InputLabel for="leagues_seasons" value="Ligas y Temporadas" />
                <TextInput id="leagues_seasons" v-model="form.leagues_seasons" class="mt-1 block w-full" />
                <InputError :message="form.errors.leagues_seasons" />
            </div>
            <div class="mt-4">
                <InputLabel for="stats" value="Estadísticas Relevantes" />
                <TextInput id="stats" v-model="form.stats" class="mt-1 block w-full" />
                <InputError :message="form.errors.stats" />
            </div>
            <div class="mt-4">
                <InputLabel for="achievements" value="Logros y Distinciones" />
                <TextInput id="achievements" v-model="form.achievements" class="mt-1 block w-full" />
                <InputError :message="form.errors.achievements" />
            </div>
            <div class="mt-4">
                <InputLabel for="about" value="Sobre mí" />
                <textarea id="about" v-model="form.about" class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-900 dark:text-white"></textarea>
                <InputError :message="form.errors.about" />
            </div>
            <PrimaryButton class="mt-4 w-full" :disabled="form.processing">Guardar</PrimaryButton>
        </form>
    </div>
</template>
