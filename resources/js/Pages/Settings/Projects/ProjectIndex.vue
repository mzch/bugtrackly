<template>
    <AuthenticatedLayout page-title="Gestions des projets">
        <template #header>Gestions des projets</template>
        <template #headerActions>
            <Link :href="route('settings.projects.create')" class="btn btn-primary btn-with-icon btn-sm">
                <PlusCircleIcon class="size-1 me-1"/>
                Ajouter un nouveau projet
            </Link>
        </template>
        <ProjectList/>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {Link} from "@inertiajs/vue3";
import ProjectList from "@/Pages/Settings/Projects/partials/ProjectList.vue";
</script>
