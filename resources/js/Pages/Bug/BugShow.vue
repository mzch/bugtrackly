<template>
    <AuthenticatedLayout page-title="bug show">
        <template #header>{{ project.name }} - <span class="text-secondary">Bug n°{{ bug.bug_id_formatted }}</span>
        </template>
        <template #headerActions>
            <BagdeStatusBug class="mt-1" :bug="bug"/>
        </template>
        <BugDescription :bug="bug" :project="project"/>
        <BugResponses :bug-responses="bug_responses"/>
    </AuthenticatedLayout>
    <ModalBugStatusUpdate :bug="bug" :project="project"/>
    <ModalBugStatusPriority :bug="bug" :project="project"/>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {computed} from "vue";
import BagdeStatusBug from "@/Components/ui/bug/BagdeStatusBug.vue";
import ModalBugStatusUpdate from "@/Pages/Bug/partial/ModalBugStatusUpdate.vue";
import ModalBugStatusPriority from "@/Pages/Bug/partial/ModalBugStatusPriority.vue";
import BugDescription from "@/Pages/Bug/partial/BugDescription.vue";
import BugResponses from "@/Pages/Bug/partial/BugResponses.vue";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    bug: {
        type: Object,
        required: true,
    },
    bug_priorities: {
        type: Array,
        required: true,
    },
    bug_status: {
        type: Array,
        required: true,
    }
})

const bug_responses = computed(() => {
    if (props.bug.bug_comments.length > 0) {
        return props.bug.bug_comments.slice(1);
    }
    return [];
})

</script>
