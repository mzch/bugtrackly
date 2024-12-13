<template>
    <NewAuthenticatedLayout page-title="Utilisateurs">
        <template #header>Création d'un utilisateur</template>
        <template #headerActions>
            <Link :href="route('settings.users.index')" class="btn btn-primary btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour aux utilisateurs
            </Link>
        </template>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto, atque distinctio dolor dolore esse
            excepturi expedita hic, laborum natus nisi officiis porro quos saepe sapiente tempore ullam vel, voluptate
            voluptatem.</p>
    </NewAuthenticatedLayout>
</template>
<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import {Link} from '@inertiajs/vue3';
import {ArrowLeftIcon} from "@heroicons/vue/24/outline/index.js";
</script>
