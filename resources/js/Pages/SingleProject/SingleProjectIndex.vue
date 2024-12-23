<template>
    <AuthenticatedLayout :page-title="project.name">
        <template #header>{{ project.name }} - <span class="text-secondary">{{ project.short_desc }}</span></template>
        <template #headerActions>
            <button class="btn btn-primary btn-with-icon btn-sm">
                <PlusCircleIcon class="size-1 me-1"/>
                Rapporter un nouveau bug
            </button>
        </template>
        <Card card-title="Liste des bugs" :remove-body-padding="true">
            <pre>project:{{project}}</pre>
        </Card>
    </AuthenticatedLayout>

</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link, usePage} from "@inertiajs/vue3";
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
const props = defineProps({
    project:{
        type:Object,
        required:true,
    }
})
</script>

<style scoped>

</style>
