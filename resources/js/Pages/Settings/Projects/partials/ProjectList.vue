<template>
    <Card :card-title="trans('settings.projects.list_title')" :remove-body-padding="true">
        <template #cardHeaderAction>
            <InputLabel for="search_user"
                        class="ms-auto col-sm-6 col-lg-8 col-xl-10 col-form-label col-form-label-sm text-end">
                {{trans('settings.projects.search')}}
            </InputLabel>
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <TextInput type="search" id="search_user" v-model="params.search" :placeholder="trans('settings.projects.search_placeholder')" class="form-control-sm" autofocus/>
            </div>
        </template>
        <template #cardFooter>
            <Pagination :items="items" item-translated-key="settings.projects.pagination" />
        </template>
        <div class="table-responsive" v-if="items.data.length">
            <table class="table table-bordered table-hover mb-0 caption-top">
                <thead>
                <tr>
                    <th :class="sortingClass('name', params)" @click="sort('name')">{{ trans('settings.projects.column.name') }}</th>
                    <th>{{ trans('settings.projects.column.users') }}</th>
                    <th class="date-col" :class="sortingClass('date', params)" @click="sort('date')">{{ trans('settings.projects.column.date') }}</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items.data" :key="item.id">
                        <td class="fw-medium">
                            <div class="d-flex align-items-center">
                                <Link class="fw-bold" :href="route('settings.projects.show', item.id)">
                                    {{item.name}}
                                </Link>
                            </div>
                            <div class="row-actions">
                                <Link :href="route('settings.projects.show', item.id)">{{ trans('general.modify_action') }}</Link>
                                <span class="mx-1 text-gray">|</span>
                                <button class="btn btn-sm btn-sm border-0 p-0 btn-link text-danger"
                                        @click="store.commit('projectsManagement/setProjectToDelete', item)"
                                        type="button">
                                    {{ trans('general.delete_action') }}
                                </button>
                            </div>
                        </td>
                        <td>
                            <AvatarsList :items="item.users"/>
                        </td>
                        <td class="text-sm text-secondary">
                            <InfoProject :project="item"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-5" v-else>
            <p class="mb-0 text-center">{{ no_result }}</p>
        </div>
    </Card>
</template>

<script setup>

import Card from "@/Components/ui/Card.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import {computed, ref, watch} from "vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import {pickBy, throttle} from "lodash";
import Pagination from "@/Components/ui/Pagination.vue";
import {sortingClass} from "@/Helpers/datatable.js";
import InfoProject from "@/Components/ui/project/InfoProject.vue";
import {useStore} from "vuex";
import AvatarsList from "@/Components/ui/user/AvatarsList.vue";
import {trans} from "@/Helpers/translations.js";

const store = useStore();
const items = computed(()=>usePage().props.projects);

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
    field: filters.value.field,
    direction: filters.value.direction
});

const no_result = computed( () => filters.value.search !== null ? trans('settings.projects.search.none_found') : trans('settings.projects.search.none_saved'))

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
    router.get(route('settings.projects.index'), my_params, {replace: true, preserveState: true})
}, 300), {deep: true})
</script>
<style scoped>
.date-col{
    width: 250px;
}
</style>
