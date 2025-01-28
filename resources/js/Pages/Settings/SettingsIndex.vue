<template>
    <AuthenticatedLayout :page-title="trans('menu.settings')">
        <template #header>{{ trans('menu.settings') }}</template>

        <div class="row">
            <div class="col-lg-4">
                <Card :card-title="trans('settings.users_title')" :card-icon="UsersIcon" class="mb-4 lg:mb-0">
                    <p>{{ trans('settings.users_desc') }}</p>
                    <p class="mb-0">
                        <Link class="btn btn-primary" :href="route('settings.users.index')">{{ trans('settings.manage_label') }}</Link>
                    </p>
                </Card>
            </div>
            <div class="col-lg-4">
                <Card :card-title="trans('settings.projects_title')" :card-icon="ComputerDesktopIcon">
                    <p>{{ trans('settings.projects_desc') }}</p>
                    <p class="mb-0">
                        <Link class="btn btn-primary" :href="route('settings.projects.index')">{{ trans('settings.manage_label') }}</Link>
                    </p>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/ui/Card.vue";
import { Link } from '@inertiajs/vue3';
import {UsersIcon, ComputerDesktopIcon} from "@heroicons/vue/24/outline/index.js";
import {trans} from "../../Helpers/translations.js";


</script>
