<template>
    <NewAuthenticatedLayout page-title="Utilisateurs">
        <template #header>Création d'un utilisateur</template>
        <template #headerActions>
            <Link :href="route('settings.users.index')" class="btn btn-primary btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour aux utilisateurs
            </Link>
        </template>
        <Card><pre>{{ user }}</pre></Card>
    </NewAuthenticatedLayout>
</template>
<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import {Link} from '@inertiajs/vue3';
import {ArrowLeftIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";

const props = defineProps({
    user:{
        type:Object,
        required:true,
    }
})
</script>
