<template>
    <AuthenticatedLayout :page-title="trans('menu.settings.users' )">
        <template #header>{{ trans('menu.settings.users' ) }}</template>
        <template #headerActions>
            <Link :href="route('settings.users.create')" class="btn btn-primary btn-with-icon btn-sm">
                <PlusCircleIcon class="size-1 me-1"/>
                {{ trans('settings.users.add_label') }}
            </Link>
        </template>
        <UsersList/>
        <UserDeleteModal/>
        <UserConnectAsModal/>
    </AuthenticatedLayout>

</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Link} from '@inertiajs/vue3';
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import UsersList from "@/Pages/Settings/Users/partials/UsersList.vue";
import UserDeleteModal from "@/Pages/Settings/Users/partials/UserDeleteModal.vue";
import UserConnectAsModal from "@/Pages/Settings/Users/partials/UserConnectAsModal.vue";
import {trans} from "../../../Helpers/translations.js";
</script>
