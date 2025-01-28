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
                <span class="badge text-bg-secondary">{{trans_choice('dashboard.project.nbBug', project.bugs_count)}}</span>
                <Link :href="route('projects.show', project.slug)" class="fw-semibold">{{ trans('dashboard.project.show') }}</Link>
            </div>
        </template>
    </Card>
</template>

<script setup>
import Card from "@/Components/ui/Card.vue";

import {ComputerDesktopIcon} from "@heroicons/vue/24/outline/index.js";
import {Link} from '@inertiajs/vue3';
import {trans, trans_choice} from "../../../Helpers/translations.js";
const props = defineProps({
    project:{
        type:Object,
        required:true
    }
})
</script>
