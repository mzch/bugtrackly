<template>
    <Card card-title="Liste des utilisateurs" :remove-body-padding="true">
        <template #cardHeaderAction>
            <InputLabel for="search_user"
                        class="ms-auto col-sm-6 col-lg-8 col-xl-10 col-form-label col-form-label-sm text-end">
                Rechercher un utilisateur :
            </InputLabel>
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <TextInput type="search" id="search_user" v-model="params.search" placeholder="Nom, prénom ou email" class="form-control-sm" autofocus/>
            </div>
        </template>
        <template #cardFooter>
            <Pagination :items="allUsers" item-singular-name="utilisateur" item-plural-name="utilisateurs"/>
        </template>
        <div class="table-responsive" v-if="allUsers.data.length">
        <table class="table table-bordered table-hover mb-0 caption-top" >
            <thead>
            <tr>
                <th :class="sortingClass('name', params)" @click="sort('name')">Nom</th>
                <th :class="sortingClass('email', params)" @click="sort('email')">Email</th>
                <th :class="sortingClass('role', params)" @click="sort('role')">Rôle</th>

            </tr>
            </thead>
            <tbody>
            <tr v-for="user in allUsers.data" :key="user.id">
                <td class="fw-medium">
                    <div class="d-flex align-items-center">
                        <Link :href="route('settings.users.show', user.id)">
                            <Avatar class="size-3 me-2" :user="user"/>
                        </Link>
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <Link class="fw-bold" :href="route('settings.users.show', user.id)">
                                    {{ user.full_name }}
                                </Link>
                                <small class="ms-1 fw-normal text-secondary text-decoration-none" v-if="user.id === $page.props.auth.user.id">(moi)</small>
                            </div>
                            <div class="row-actions">
                                <Link :href="route('settings.users.show', user.id)">Modifier</Link>

                                <template v-if="$page.props.auth.user.id !== user.id">
                                    <span class="mx-1 text-gray">|</span>
                                    <button class="btn btn-sm btn-sm border-0 p-0 btn-link text-danger"
                                            @click="store.commit('usersManagement/setUserToDelete', user)" type="button">
                                        Supprimer
                                    </button>
                                    <span class="mx-1 text-gray">|</span>
                                    <button class="btn btn-sm btn-sm border-0 p-0 btn-link"
                                            @click="store.commit('usersManagement/setUserToConnectAs', user)" type="button">
                                        Se connecter comme
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                </td>
                <td>{{user.email}}</td>
                <td><BadgeRole :role="user.role"/></td>
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
import Pagination from "@/Components/ui/Pagination.vue";
import {sortingClass} from "@/Helpers/datatable.js";
import BadgeRole from "@/Components/ui/user/BadgeRole.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {pickBy, throttle} from "lodash";
import {useStore} from "vuex";
const store = useStore();


const allUsers = computed(()=>usePage().props.users);

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

const no_result = computed( () => filters.value.search !== null ? "Aucun utilisateur trouvé" : "Aucun utilisateur enregistré")


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
    router.get(route('settings.users.index'), my_params, {replace: true, preserveState: true})
}, 300), {deep: true})
</script>
