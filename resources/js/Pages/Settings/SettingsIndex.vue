<template>
    <AuthenticatedLayout page-title="Utilisateurs">
        <template #header>Paramètres</template>

        <div class="row">
            <div class="col-lg-4">
                <Card card-title="Utilisateurs" :card-icon="UsersIcon" class="mb-4 lg:mb-0">
                    <p>Administrez efficacement vos utilisateurs et gérez leurs droits en toute simplicité.</p>
                    <p class="mb-0">
                        <Link class="btn btn-primary" :href="route('settings.users.index')">Gérer</Link>
                    </p>
                </Card>
            </div>
            <div class="col-lg-4">
                <Card card-title="Projets" :card-icon="ComputerDesktopIcon">
                    <p>Supervisez et pilotez vos projets avec précision et efficacité.</p>
                    <p class="mb-0">
                        <Link class="btn btn-primary" :href="route('settings.projects.index')">Gérer</Link>
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


</script>
