<template>
    <NewAuthenticatedLayout page-title="Utilisateurs">
        <template #header>Gestion des utilisateurs</template>
        <template #headerActions>
            <Link :href="route('settings.users.create')" class="btn btn-primary btn-with-icon">
                <PlusCircleIcon class="size-1 me-1"/>
                Ajouter un utilisateur
            </Link>
        </template>
        <UsersList/>
        <UserDeleteModal/>
    </NewAuthenticatedLayout>

</template>
<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import {Link} from '@inertiajs/vue3';
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import UsersList from "@/Pages/Settings/Users/partials/UsersList.vue";
import UserDeleteModal from "@/Pages/Settings/Users/partials/UserDeleteModal.vue";
</script>
