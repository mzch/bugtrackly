<template>
    <AuthenticatedLayout page-title="Single project">
        <pre>{{usePage().url}}</pre>
        <pre>project:{{project}}</pre>
    </AuthenticatedLayout>

</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {usePage} from "@inertiajs/vue3";
const props = defineProps({
    project:{
        type:Object,
        required:true,
    }
})
</script>

<style scoped>

</style>
