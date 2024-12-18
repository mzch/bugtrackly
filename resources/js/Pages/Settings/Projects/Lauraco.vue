<template>
    <AuthenticatedLayout page-title="Lauraco">
        <template #header>Mes projets / Lauraco</template>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto est, ipsum molestias nisi nulla possimus
            repellat sint velit? Debitis eveniet itaque minima modi officia pariatur quo sit tempore ut vitae?</p>
        <hr>
        {{route().current()}}
        <br>
        {{$page.component}}

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>
