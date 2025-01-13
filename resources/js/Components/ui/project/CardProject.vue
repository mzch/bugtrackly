<template>
    <Card class="project-card">
        <template #cardImg v-if="project.project_photo_url">
            <Link :href="route('projects.show', project.slug)">
                <img :src="project.project_photo_url" class="card-img-top" alt="">
            </Link>
        </template>
        <template #cardImg  v-else>
            <Link :href="route('projects.show', project.slug)">
                <span class="illustration-default"><ComputerDesktopIcon class="size-5"/></span>
            </Link>
        </template>
        <p class="mb-0 fw-semibold">
            {{project.name}}
        </p>
        <p class="text-sm text-secondary mb-0">{{project.short_desc}}</p>
        <template #cardFooter>
            <div class="d-flex justify-content-between align-items-center">
                <spa class="badge text-bg-secondary">{{str_nb_bug(project)}}</spa>
                <Link :href="route('projects.show', project.slug)" class="fw-semibold">Voir</Link>
            </div>
        </template>
    </Card>
</template>

<script setup>
import Card from "@/Components/ui/Card.vue";

import {ComputerDesktopIcon} from "@heroicons/vue/24/outline/index.js";
import {Link} from '@inertiajs/vue3';
const props = defineProps({
    project:{
        type:Object,
        required:true
    }
})
const str_nb_bug = (project) => {
    if(project.bugs_count === 0){
        return "Aucun bug ouvert !";
    }
    return project.bugs_count > 1 ? `${project.bugs_count} bugs ouverts` : `1 bug ouvert`
}
</script>
