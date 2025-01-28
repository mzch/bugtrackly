<template>
    <AuthenticatedLayout :page-title="trans('menu.settings.projects')">
        <template #header>{{ trans('menu.settings.projects') }}</template>
        <template #headerActions>
            <Link :href="route('settings.projects.create')" class="btn btn-primary btn-with-icon btn-sm">
                <PlusCircleIcon class="size-1 me-1"/>
                {{ trans('settings.projects.add_project_label') }}
            </Link>
        </template>
        <ProjectList/>
        <ProjectDeleteModal/>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {Link} from "@inertiajs/vue3";
import ProjectList from "@/Pages/Settings/Projects/partials/ProjectList.vue";
import ProjectDeleteModal from "@/Pages/Settings/Projects/partials/ProjectDeleteModal.vue";
import {trans} from "../../../Helpers/translations.js";
</script>
