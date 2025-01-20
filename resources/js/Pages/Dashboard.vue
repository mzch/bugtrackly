<template>
  <AuthenticatedLayout page-title="Dashboard">
    <template #header>Tableau de bord</template>

      <Card class="mb-4">
          <div class="row justify-content-start">
              <div class="col-lg-4" v-for="project in projects">
                  <CardProject :project="project"/>
              </div>
          </div>
      </Card>
      <Card card-title="Liste des bug suivis" :remove-body-padding="true" v-if="followed_bugs.data.length || has_filter_active">
          <template #cardHeaderAction>
              <template v-if="projects_options.length > 2">
              <InputLabel for="project_filter" class="col-auto col-form-label col-form-label-sm">
                  Projets :
              </InputLabel>
              <FormSelect id="project_filter"
                          class="col-auto form-select-sm w-auto me-1"
                          :display-select-label-option="false"
                          :options="projects_options"
                          v-model="params.project"/>
              </template>
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
          <div class="table-responsive" v-if="followed_bugs.data.length">
            <table class="table table-bordered table-hover mb-0 caption-top" >
              <thead>
                  <tr>
                      <th :class="sortingClass('id', params)" @click="sort('id')">#</th>
                      <th :class="sortingClass('project', params)" @click="sort('project')">Projet</th>
                      <th :class="sortingClass('title', params)" @click="sort('title')">Titre</th>
                      <th :class="sortingClass('status', params)" @click="sort('status')">Statut</th>
                      <th>Assigné à</th>
                      <th :class="sortingClass('date', params)" @click="sort('date')">Date</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="bug in followed_bugs.data" :key="bug.id">
                      <td>
                          <span class="badge text-bg-light fw-light">
                        {{ bug.bug_id_formatted }}
                        </span>
                      </td>
                      <td>
                          <Link :href="route('projects.show', [bug.project.slug])" class="fw-bold bug_title">
                          {{bug.project.name}}
                          </Link>
                      </td>
                      <td class="align-middle">
                          <p class="mb-0 d-flex flex-column align-items-start">
                              <Link :href="route('projects.bug.show', [bug.project.slug, bug.id])" class="fw-bold bug_title d-flex align-items-center">
                                  <StarIcon class="size-1 text-status-in_progress me-1"/>
                                  {{ bug.title }}
                              </Link>
                              <small class="text-secondary">{{ getPriorityObject(bug.priority)?.extended_label}}</small>
                          </p>
                      </td>
                      <td class="align-middle">
                          <BagdeStatusBug :bug="bug"/>
                      </td>
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
          <template #cardFooter>
              <Pagination :items="followed_bugs" item-singular-name="bug suivi" item-plural-name="bugs suivis"/>
          </template>
      </Card>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/ui/Card.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {Link, usePage, router} from "@inertiajs/vue3";
import CardProject from "@/Components/ui/project/CardProject.vue";
import Pagination from "@/Components/ui/Pagination.vue";
import {sortingClass} from "@/Helpers/datatable.js";
import {getPriorityObject} from "@/Helpers/bug.js";
import {StarIcon} from "@heroicons/vue/24/solid/index.js";
import BagdeStatusBug from "@/Components/ui/bug/BagdeStatusBug.vue";
import InfoDateBug from "@/Components/ui/bug/InfoDateBug.vue";
import {computed, ref, watch} from "vue";
import {pickBy, throttle} from "lodash";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";

const props = defineProps({
    projects:{
        type:Array,
        required:true,
    },
    followed_bugs:{
        type:Object,
        required:true,
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

/**
 * Filter received from the controller
 * @type {ComputedRef<unknown>}
 */
const filters = computed(() => usePage().props.filters);
const has_filter_active = computed(() => {
    return filters.value.priority !== null || filters.value.project !== null || filters.value.status !== null || filters.value.search !== null
})

const projects_options = computed(() => {
    const projects = props.projects || [];
    const projects_opt = projects.map(p => ({id: p.slug, label: p.name}));
    return [...[{id: null, label: 'Tous'}], ...projects_opt];
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
const no_result = computed(() => filters.value.search !== null ? "Aucun bug suivi trouvé" : "Aucun bug suivi enregistré")
/**
 * Params send to the controller
 * @type {Ref<UnwrapRef<{search, field: *, direction}>>}
 */
const params = ref({
    search: filters.value.search,
    priority: filters.value.priority,
    project: filters.value.project,
    status: filters.value.status,
    field: filters.value.field,
    direction: filters.value.direction
});

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
    router.get(route('dashboard'), my_params, {replace: true, preserveState: true})
}, 300), {deep: true})
</script>
