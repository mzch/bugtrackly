<template>
    <AuthenticatedLayout :page-title="bug.title">
        <template #header>
            {{ project.name }} - <span class="text-secondary">Bug n°{{ bug.bug_id_formatted }}</span>
            <button type="button"
                    :title="trans('bug.show.tracking_desc')"
                    class="btn btn-secondary btn-sm ms-2 btn-with-icon rounded-pill"
                    @click="toggleFollowBug()"
                    :disabled="form.processing"
                    v-if="!isFollowing">
                <PlusIcon class="size-1 me-1"/>
                {{ trans('bug.show.tracking_label') }}
            </button>
            <button type="button"
                    title="Ne plus suivre ce bug"
                    class="btn btn-secondary btn-sm ms-2 btn-with-icon rounded-pill"
                    @click="toggleFollowBug()"
                    :disabled="form.processing"
                    v-else>
                <CheckIcon class="size-1 me-1"/>
                {{ trans('bug.show.tracked_label') }}
            </button>

        </template>
        <template #headerActions>
            <Link :href="route('projects.bug.create', project.slug)" class="btn btn-primary btn-with-icon btn-sm">
                <PlusCircleIcon class="size-1 me-1"/>
                {{ trans('project.show.report_label') }}
            </Link>
        </template>

        <BugDescription :bug="bug" :project="project" :is-following="isFollowing"/>
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
import {computed, watch} from "vue";
import BugDescription from "@/Pages/Bug/partial/BugDescription.vue";
import BugResponses from "@/Pages/Bug/partial/BugResponses.vue";
import ModalDeleteResponse from "@/Pages/Bug/partial/ModalDeleteResponse.vue";
import ModalDeleteBug from "@/Pages/Bug/partial/ModalDeleteBug.vue";
import {formatDate} from "../../Helpers/date.js";
import Card from "@/Components/ui/Card.vue";
import ModalDeleteFile from "@/Pages/Bug/partial/ModalDeleteFile.vue";
import {CheckIcon, PlusIcon, PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {router, useForm, Link} from "@inertiajs/vue3";
import {trans} from "@/Helpers/translations.js";

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
    },
    isFollowing: {
        type: Boolean,
        required: true,
    }
})

const bug_responses = computed(() => {
    if (props.bug.bug_comments.length > 0) {
        return props.bug.bug_comments.slice(1);
    }
    return [];
})
const form = useForm({
    followBug:!props.isFollowing,
})
const toggleFollowBug = () => {
    const urlParams = route().params;
    form.post(route('projects.bug.toggleFollowBug', [urlParams.project, urlParams.bug]))
}

watch(() => props.isFollowing, (newValue) => {
    console.log(newValue);
    if(newValue === false){
        form.defaults('followBug', true);
    }else{
        form.defaults('followBug', false);
    }
    form.reset();
})

</script>
