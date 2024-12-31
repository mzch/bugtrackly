<template>
    <AuthenticatedLayout page-title="bug show">
        <template #header>{{ project.name }} - <span class="text-secondary">Bug n°{{ formatBugId(bug.id) }}</span></template>
        <Card :card-title="bug.title">
            <pre>{{bug}}</pre>
        </Card>
    </AuthenticatedLayout>
</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {formatBugId} from "../../Helpers/bug.js";
import Card from "@/Components/ui/Card.vue";

const props = defineProps({
    project:{
        type:Object,
        required:true,
    },
    bug:{
        type:Object,
        required:true,
    },
    bug_priorities:{
        type:Array,
        required:true,
    }
})
</script>

<style scoped>

</style>
