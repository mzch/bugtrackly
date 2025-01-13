<template>
    <AuthenticatedLayout :page-title="bug.title">
        <template #header>{{ project.name }} - <span class="text-secondary">Bug n°{{ bug.bug_id_formatted }}</span>
        </template>
        <template #headerActions>
            <BagdeStatusBug class="mt-1" :bug="bug"/>
        </template>

        <BugDescription :bug="bug" :project="project"/>
        <BugResponses :bug-responses="bug_responses" class="mb-4"/>

        <Card card-title="Historique" remove-body-padding>
            <table class="table table-bordered table-sm text-sm">
                <thead>
                <tr>
                    <th>Date de modification</th>
                    <th>Utilisateur</th>
                    <th>Changement</th>
                    <th>Détail</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="log in bug.bug_logs" :key="log.id">
                    <td>{{ formatDate(log.created_at, "d MMMM yyyy à HH'h'mm") }}</td>
                    <td>{{log.user.full_name}}</td>
                    <td>{{log.action}}</td>
                    <td>{{log.details}}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    </AuthenticatedLayout>
    <ModalDeleteResponse/>
    <ModalDeleteBug/>
    <ModalDeleteFile/>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {computed} from "vue";
import BagdeStatusBug from "@/Components/ui/bug/BagdeStatusBug.vue";
import BugDescription from "@/Pages/Bug/partial/BugDescription.vue";
import BugResponses from "@/Pages/Bug/partial/BugResponses.vue";
import ModalDeleteResponse from "@/Pages/Bug/partial/ModalDeleteResponse.vue";
import ModalDeleteBug from "@/Pages/Bug/partial/ModalDeleteBug.vue";
import {formatDate} from "../../Helpers/date.js";
import Card from "@/Components/ui/Card.vue";
import ModalDeleteFile from "@/Pages/Bug/partial/ModalDeleteFile.vue";

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
