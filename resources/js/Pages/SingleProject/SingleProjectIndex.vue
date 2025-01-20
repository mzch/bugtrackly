<template>
    <AuthenticatedLayout :page-title="project.name">
        <template #header>{{ project.name }} - <span class="text-secondary">{{ project.short_desc }}</span></template>
        <template #headerActions>
            <Link :href="route('projects.bug.create', project.slug)" class="btn btn-primary btn-with-icon btn-sm">
                <PlusCircleIcon class="size-1 me-1"/>
                Rapporter un nouveau bug
            </Link>
        </template>
        <Card card-title="Liste des bugs" :remove-body-padding="true">
            <template #cardHeaderAction>
                <InputLabel for="priority_filter" class="col-auto col-form-label col-form-label-sm">
                    Priorité :
                </InputLabel>
                <FormSelect id="priority_filter"
                            class="col-auto form-select-sm w-auto me-1"
                            :options="priorities_options"
                            :display-select-label-option="false"
                            v-model="params.priority"/>

                <InputLabel for="priority_filter" class="col-auto col-form-label col-form-label-sm">
                    Status :
                </InputLabel>
                <FormSelect id="priority_filter"
                            class="col-auto form-select-sm w-auto me-1"
                            :options="status_options"
                            :display-select-label-option="false"
                            v-model="params.status"/>

                <InputLabel for="search_user"
                            class="col-form-label col-form-label-sm text-end col-auto">
                    Rechercher un bug :
                </InputLabel>
                <div class="col-auto">
                    <TextInput type="search" id="search_user" v-model="params.search" placeholder="Numéro ou titre"
                               class="form-control-sm" autofocus/>
                </div>
            </template>
            <template #cardFooter>
                <Pagination :items="bugs" item-singular-name="bug" item-plural-name="bugs"/>
            </template>
            <div class="table-responsive" v-if="bugs.data.length">
                <table class="table table-bordered table-hover mb-0 caption-top">
                    <thead>
                    <tr>
                        <th :class="sortingClass('id', params)" @click="sort('id')">#</th>
                        <th :class="sortingClass('title', params)" @click="sort('title')">Titre</th>
                        <th :class="sortingClass('status', params)" @click="sort('status')">Statut</th>
                        <th>Notes</th>
                        <th>Assigné à</th>
                        <th :class="sortingClass('date', params)" @click="sort('date')">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="bug in bugs.data" :key="bug.id">
                        <td>
                        <span class="badge text-bg-light fw-light">
                        {{ bug.bug_id_formatted }}
                        </span>
                        </td>
                        <td class="align-middle">
                            <p class="mb-0 d-flex flex-column align-items-start">
                                <Link :href="route('projects.bug.show', [project.slug, bug.id])" class="fw-bold bug_title d-flex align-items-center">
                                    <StarIcon class="size-1 text-status-in_progress me-1" v-if="bug.is_followed_by_me"/>
                                    {{ bug.title }}
                                </Link>
                                <small class="text-secondary">{{ getPriorityObject(bug.priority)?.extended_label}}</small>

                            </p>
                        </td>
                        <td class="align-middle">
                            <BagdeStatusBug :bug="bug"/>
                        </td>

                        <td class="text-secondary text-sm text-center align-middle"><span class="badge text-bg-secondary rounded-pill">{{nb_notes(bug.bug_comments_count)}}</span></td>
                        <td class="text-secondary text-sm text-center align-middle">
                            <div class="d-flex align-items-center" v-if="bug.assigned_user">
                                <Avatar :user="bug.assigned_user" class="me-1 bordered"/>
                                {{ bug.assigned_user.full_name }}
                            </div>
                            <em class="mb-0 opacity-75" v-else>Non assigné</em>
                        </td>

                        <td class="text-sm text-secondary">
                            <InfoDateBug :bug="bug"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-5" v-else>
                <p class="mb-0 text-center">{{ no_result }}</p>
            </div>
        </Card>
    </AuthenticatedLayout>

</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
import {computed, ref, watch} from "vue";
import {router, Link, usePage} from "@inertiajs/vue3";
import InfoDateBug from "@/Components/ui/bug/InfoDateBug.vue";
import BagdeStatusBug from "@/Components/ui/bug/BagdeStatusBug.vue";
import Pagination from "@/Components/ui/Pagination.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import {sortingClass} from "@/Helpers/datatable.js";
import {pickBy, throttle} from "lodash";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {StarIcon} from "@heroicons/vue/24/solid/index.js";
import {getPriorityObject, nb_notes} from "../../Helpers/bug.js";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    bugs: {
        type: Object,
        required: true,
    },
    bug_status: {
        type: Array,
        required: true,
    },
    bug_priorities: {
        type: Array,
        required: true,
    }
})

const priorities_options = computed(() => {
    const priorities = props.bug_priorities || [];
    const priorities_opt = priorities.map(p => ({id: p.slug, label: p.label}));
    return [...[{id: null, label: 'Toutes'}], ...priorities_opt];
});

const status_options = computed(() => {
    const status = props.bug_status || [];
    const status_opt = status.map(s => ({id: s.slug, label: s.label}));
    return [...[{id: 'all', label: 'Tous'}, {id: null, label: 'Ouvert'}], ...status_opt];
});

/**
 * Filter received from the controller
 * @type {ComputedRef<unknown>}
 */
const filters = computed(() => usePage().props.filters);

/**
 * Params send to the controller
 * @type {Ref<UnwrapRef<{search, field: *, direction}>>}
 */
const params = ref({
    search: filters.value.search,
    priority: filters.value.priority,
    status: filters.value.status,
    field: filters.value.field,
    direction: filters.value.direction
});

const no_result = computed(() => filters.value.search !== null ? "Aucun bug trouvé" : "Aucun bug enregistré")

/**
 * Sort handler on columns header
 * @param field
 */
const sort = (field) => {
    params.value.field = field
    params.value.direction = params.value.direction === 'asc' ? 'desc' : 'asc';
}

/**
 * Watcher for params
 * Make an Inertia request with cleaned params
 */
watch(params, throttle(function () {
    //clean empty params
    const my_params = pickBy(params.value);

    //request
    router.get(route('projects.show', props.project.slug), my_params, {replace: true, preserveState: true})
}, 300), {deep: true})
</script>

<style scoped>

</style>
