<template>
    <AuthenticatedLayout page-title="Gestions des projets">
        <template #header>Gestions des projets</template>
        <template #headerActions>
            <Link :href="route('settings.projects.index')" class="btn btn-primary btn-sm btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour aux projets
            </Link>
        </template>
       <pre>{{project}}</pre>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {ArrowLeftIcon, PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {Link} from "@inertiajs/vue3";
const props = defineProps({
    project:{
        type:Object,
        required:true,
    }
})
</script>
