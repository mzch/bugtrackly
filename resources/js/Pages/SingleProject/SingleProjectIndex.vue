<template>
    <AuthenticatedLayout :page-title="project.name">
        <template #header>{{ project.name }} - <span class="text-secondary">{{ project.short_desc }}</span></template>
        <template #headerActions>
            <Link :href="route('projects.bug.create', project.slug)" class="btn btn-primary btn-with-icon btn-sm">
                <PlusCircleIcon class="size-1 me-1"/>
                {{ trans('project.show.report_label') }}
            </Link>
        </template>
        <Card :card-title="trans('project.show.ticket_list_title')" :remove-body-padding="true">
            <template #cardHeaderAction>
                <template v-if="projects_has_tickets_categories">
                    <InputLabel for="category_filter" class="col-auto col-form-label col-form-label-sm">
                        Category :
                    </InputLabel>
                    <FormSelect id="category_filter"
                                class="col-auto form-select-sm w-auto me-1"
                                :options="tickets_categories_options"
                                :display-select-label-option="false"
                                v-model="params.category"/>
                </template>
                <InputLabel for="priority_filter" class="col-auto col-form-label col-form-label-sm">
                    {{ trans('tickets_list.filters.priority') }}
                </InputLabel>
                <FormSelect id="priority_filter"
                            class="col-auto form-select-sm w-auto me-1"
                            :options="priorities_options"
                            :display-select-label-option="false"
                            v-model="params.priority"/>

                <InputLabel for="priority_filter" class="col-auto col-form-label col-form-label-sm">
                    {{ trans('tickets_list.filters.status') }}
                </InputLabel>
                <FormSelect id="priority_filter"
                            class="col-auto form-select-sm w-auto me-1"
                            :options="status_options"
                            :display-select-label-option="false"
                            v-model="params.status"/>

                <InputLabel for="search_user"
                            class="col-form-label col-form-label-sm text-end col-auto">
                    {{ trans('tickets_list.filters.search') }}
                </InputLabel>
                <div class="col-auto">
                    <TextInput type="search" id="search_user" v-model="params.search" :placeholder="trans('tickets_list.filters.search_placeholder')"
                               class="form-control-sm" autofocus/>
                </div>
            </template>
            <template #cardFooter>
                <Pagination :items="bugs" item-translated-key="project.ticket_list_pagination" />
            </template>
            <div class="table-responsive" v-if="bugs.data.length">
                <table class="table table-bordered table-hover mb-0 caption-top">
                    <thead>
                    <tr>
                        <th :class="sortingClass('id', params)" @click="sort('id')">#</th>
                        <th :class="sortingClass('title', params)" @click="sort('title')">
                            {{ trans('tickets_list.headings.title') }}
                        </th>
                        <th v-if="projects_has_tickets_categories">Cat</th>
                        <th :class="sortingClass('status', params)" @click="sort('status')">
                            {{ trans('tickets_list.headings.status') }}
                        </th>
                        <th>{{ trans('tickets_list.headings.assigned') }}</th>
                        <th style="width: 100px" :class="sortingClass('priority', params)" @click="sort('priority')">
                            {{ trans('tickets_list.headings.priority') }}
                        </th>
                        <th :class="sortingClass('date', params)" @click="sort('date')">
                            {{ trans('tickets_list.headings.date') }}
                        </th>
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

                                <small class="text-secondary">
                                    <ChatBubbleLeftIcon class="size-1"/>
                                    {{trans_choice('tickets_list.nb_notes', nb_notes(bug.bug_comments_count))}}
                                </small>

                            </p>
                        </td>
                        <td class="text-center align-middle" v-if="projects_has_tickets_categories">
                            <span class="badge text-bg-light" v-if="bug.ticket_category">
                                <TagIcon class="size-sm"/>
                                {{bug.ticket_category.name}}
                            </span>
                            <span v-else>-</span>
                        </td>
                        <td class="align-middle">
                            <BagdeStatusBug :bug="bug"/>
                        </td>
                        <td class="text-secondary text-sm text-center align-middle">
                            <div class="d-flex align-items-center" v-if="bug.assigned_user">
                                <Avatar :user="bug.assigned_user" class="me-1 bordered"/>
                                {{ bug.assigned_user.full_name }}
                            </div>
                            <em class="mb-0 opacity-75" v-else>{{ trans('tickets_list.not_assigned') }}</em>
                        </td>
                        <td class="align-middle">
                            <div class="priority rounded-pill"
                                 data-bs-toggle="tooltip"
                                 data-bs-placement="bottom"
                                 :data-bs-title="getPriorityObject(bug.priority)?.extended_label"
                                 :class="bug_priority_class(bug)"></div>

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
import {PlusCircleIcon, TagIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
import {computed, onMounted, onUnmounted, ref, watch} from "vue";
import {router, Link, usePage} from "@inertiajs/vue3";
import InfoDateBug from "@/Components/ui/bug/InfoDateBug.vue";
import BagdeStatusBug from "@/Components/ui/bug/BagdeStatusBug.vue";
import Pagination from "@/Components/ui/Pagination.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import {sortingClass} from "@/Helpers/datatable.js";
import {map, pickBy, throttle} from "lodash";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {StarIcon} from "@heroicons/vue/24/solid/index.js";
import {bug_priority_class, getPriorityObject, nb_notes, nb_notes_labels} from "../../Helpers/bug.js";
import {ChatBubbleLeftIcon} from "@heroicons/vue/24/outline/index.js";
import {disposeToolTips, enableToolTips} from "@/Helpers/bs_tooltips.js";
import {trans, trans_choice} from "../../Helpers/translations.js";

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

const projects_has_tickets_categories = computed(() => props.project.ticket_categories.length > 0)
const tickets_categories_options = computed(() => {
    const ticket_categories = map(props.project.ticket_categories, cat => ({id:cat.id, label:cat.name})) || [];
    return [{id:null, label:'Toutes'}, ...ticket_categories, {id:'none', label: "Non attribuée"}];
});

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
    direction: filters.value.direction,
    category:filters.value.category,
});

const no_result = computed(() => filters.value.search !== null ? trans('tickets_list.none_found') : trans('tickets_list.none_saved'))

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
    console.log(params.value);
    const my_params = pickBy(params.value);
    console.log(my_params);
    //request
    router.get(route('projects.show', props.project.slug), my_params, {replace: true, preserveState: true})
}, 300), {deep: true})
const tooltipList = ref([]);
onMounted(() => enableToolTips(tooltipList))
onUnmounted(() => disposeToolTips(tooltipList))
</script>

<style scoped>

</style>
