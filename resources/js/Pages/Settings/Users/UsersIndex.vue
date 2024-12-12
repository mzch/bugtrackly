<template>
    <NewAuthenticatedLayout page-title="Utilisateurs">
        <template #header>Gestion des utilisateurs</template>
        <template #headerActions>
            <Link :href="route('settings.users.create')" class="btn btn-primary btn-with-icon">
                <PlusCircleIcon class="size-1 me-1"/>
                Ajouter un utilisateur
            </Link>
        </template>
        <Card card-title="Liste des utilisateurs" :remove-body-padding="true">
            <template #cardHeaderAction>
                <InputLabel for="search_user"
                            class="ms-auto col-sm-6 col-lg-8 col-xl-10 col-form-label col-form-label-sm text-end">
                    Rechercher un utilisateur :
                </InputLabel>
                <div class="col-sm-6 col-lg-4 col-xl-2">
                    <TextInput id="search_user" placeholder="Nom, prénom ou email" class="form-control-sm" autofocus/>
                </div>
            </template>
            <template #cardFooter>
                <small>Pagination</small>
            </template>
            <table class="table table-bordered mb-0 caption-top" v-if="allUsers.data.length">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>

                </tr>
                </thead>
                <tbody>
                <tr v-for="user in allUsers.data" :key="user.id">
                    <td class="fw-medium">
                        <div class="d-flex align-items-center">
                            <Link :href="route('settings.users.index', user.id)">
                                <Avatar class="size-3 me-2" :user="user"/>
                            </Link>
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <Link class="fw-bold" :href="route('settings.users.show', user.id)">
                                        {{ user.full_name }}
                                    </Link>
                                    <small class="ms-1 fw-normal text-body text-decoration-none" v-if="user.id === $page.props.auth.user.id">(moi)</small>
                                </div>
                                <div class="row-actions">
                                    <Link :href="route('settings.users.show', user.id)">Modifier</Link>

                                    <template v-if="$page.props.auth.user.id !== user.id">
                                        <span class="mx-1">|</span>
                                        <button class="btn btn-sm btn-sm border-0 p-0 btn-link text-danger"
                                                @click="emit('deleteItem', user)" type="button">
                                            Supprimer
                                        </button>
                                        <span class="mx-1">|</span>
                                        <button class="btn btn-sm btn-sm border-0 p-0 btn-link"
                                                @click="emit('connectAs', user)" type="button">
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
        </Card>
    </NewAuthenticatedLayout>
</template>
<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import {Link, usePage} from '@inertiajs/vue3';
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import BadgeRole from "@/Components/ui/user/BadgeRole.vue";
const allUsers = usePage().props.users;
</script>
