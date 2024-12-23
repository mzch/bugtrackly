<template>
    <Card class="project-card">
        <template #cardImg v-if="project.project_photo_url">
            <img :src="project.project_photo_url" class="card-img-top" alt="">
        </template>
        <template #cardImg  v-else>
            <span class="illustration-default"><ComputerDesktopIcon class="size-5"/></span>
        </template>
        <p class="mb-0 fw-semibold">
            {{project.name}}
        </p>
        <p class="text-sm text-secondary mb-0">{{project.short_desc}}</p>
        <template #cardFooter>
            <div class="d-flex justify-content-end">
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
</script>

<style scoped>

</style>
