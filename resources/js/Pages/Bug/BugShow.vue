<template>
    <AuthenticatedLayout page-title="bug show">
        <template #header>{{ project.name }} - <span class="text-secondary">Bug n°{{ bug.bug_id_formatted }}</span></template>
        <template #headerActions>
            <BagdeStatusBug class="mt-1" :bug="bug"/>
        </template>
        <Card :card-title="bug.title" class="mb-4">
            <template #cardHeaderAction>
                <div class="col-auto ms-auto">
                <BadgePriorityBug :add-priority-label="true" :bug="bug"/>
                </div>
            </template>
            <div class="text-secondary mb-3" v-if="bug.user">
                <div class="d-flex align-items-center">
                    <Avatar :user="bug.user" class="bordered me-1"/>
                    <span class="fw-semibold me-1">{{bug.user.full_name}}</span>
                </div>
            </div>
            <p>{{first_bug_comment.content}}</p>
            <p class="text-sm text-secondary mb-0">{{formatDate(bug.created_at, "d MMMM yyyy à HH'h'mm")}}</p>
            <template #cardFooter>
                <div>
                    <SecondaryButton  type="button" class="btn-sm me-2" @click="store.commit('bug/setBugToUpdateStatus', props.bug)" v-if="canUpdateBugStatus">
                        Modifier le statut
                    </SecondaryButton>
                    <!--                <SecondaryButton outlined type="button" class="btn-sm">
                                        Modifier la priorité
                                    </SecondaryButton>-->
                </div>
            </template>
        </Card>
        <Card card-title="Réponses">
            <div v-if="bug_responses.length">
                <Card v-for="(response, index) in bug_responses" :key="response.id" :class="{'mb-4' : index< bug_responses.length -1 }">
                    <div class="d-flex align-items-center text-secondary mb-3">
                        <Avatar :user="response.user" class="bordered me-1"/>
                        <span class="fw-semibold me-1">{{response.user.full_name}}</span>
                    </div>
                    <p>{{response.content}}</p>
                    <p class="text-sm text-secondary mb-0">{{formatDate(response.created_at, "d MMMM yyyy à HH'h'mm")}}</p>
                </Card>
            </div>
        </Card>
    </AuthenticatedLayout>
    <ModalBugStatusUpdate :bug="bug" :project="project"/>
</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import Card from "@/Components/ui/Card.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {formatDate} from "@/Helpers/date.js";
import {computed} from "vue";
import BagdeStatusBug from "@/Components/ui/bug/BagdeStatusBug.vue";
import BadgePriorityBug from "@/Components/ui/bug/BadgePriorityBug.vue";
import SecondaryButton from "@/Components/ui/form/SecondaryButton.vue";
import {hasRole} from "@/Helpers/users.js";

import ModalBugStatusUpdate from "@/Pages/Bug/partial/ModalBugStatusUpdate.vue";
import {useStore} from "vuex";
const store = useStore();
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

const first_bug_comment = computed(() => props.bug.bug_comments[0] ?? false);
const bug_responses = computed(() => {
    if(props.bug.bug_comments.length > 0){
        return props.bug.bug_comments.slice(1);
    }
    return [];
})


const canUpdateBugStatus = computed(() => {
    // un admin voir toujours ce bouton
    if(hasRole('admin')){
        return true;
    }

    // un reporter ne voit ce bouton que si le bug est Rejeté (3), Résolu (5) ou Fermé (6)
    const status_for_reporter = [3,5, 6]
    return status_for_reporter.includes(props.bug.status);
})
</script>
