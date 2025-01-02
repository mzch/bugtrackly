<template>
    <AuthenticatedLayout page-title="bug show">
        <template #header>{{ project.name }} - <span class="text-secondary">Bug n°{{ bug.bug_id_formatted }}</span></template>
        <template #headerActions>
            <BagdeStatusBug class="mt-1" :bug="bug"/>
        </template>
        <Card :card-title="bug.title">
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
            <p class="text-sm text-secondary" :class="{'mb-0': !bug_responses.length}">{{formatDate(bug.created_at, "d MMMM yyyy à HH'h'mm")}}</p>
            <div v-if="bug_responses.length">

                <div v-for="(response, index) in bug_responses" :key="response.id">
                    <hr>
                    <div class="d-flex align-items-center text-secondary mb-3">
                        <Avatar :user="response.user" class="bordered me-1"/>
                        <span class="fw-semibold me-1">{{response.user.full_name}}</span>
                    </div>
                    <p>{{response.content}}</p>
                    <p class="text-sm text-secondary" :class="{'mb-0': index >= bug_responses.length -1}">{{formatDate(response.created_at, "d MMMM yyyy à HH'h'mm")}}</p>
                </div>

            </div>
        </Card>
    </AuthenticatedLayout>
</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import Card from "@/Components/ui/Card.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {formatDate} from "@/Helpers/date.js";
import {computed} from "vue";
import BagdeStatusBug from "@/Components/ui/bug/BagdeStatusBug.vue";
import BadgePriorityBug from "@/Components/ui/bug/BadgePriorityBug.vue";

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
    }
})
//<span>le </span>
const first_bug_comment = computed(() => props.bug.bug_comments[0] ?? false);
const bug_responses = computed(() => {
    if(props.bug.bug_comments.length > 0){
        return props.bug.bug_comments.slice(1);
    }
    return [];
})
</script>
