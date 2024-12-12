<template>
    <NewAuthenticatedLayout page-title="Utilisateurs">
        <template #header>Paramètres</template>

        <div class="row">
            <div class="col-lg-4">
                <Card card-title="Utilisateurs" class="mb-4 lg:mb-0">
                    <p>Administrez efficacement vos utilisateurs et gérez leurs droits en toute simplicité.</p>
                    <p class="mb-0">
                        <Link class="btn btn-primary" :href="route('settings.users.index')">Gérer</Link>
                    </p>
                </Card>
            </div>
            <div class="col-lg-4">
                <Card card-title="Projets">
                    <p>Supervisez et pilotez vos projets avec précision et efficacité.</p>
                    <p class="mb-0">
                        <Link class="btn btn-primary" :href="route('settings.projects.index')">Gérer</Link>
                    </p>
                </Card>
            </div>
        </div>
    </NewAuthenticatedLayout>
</template>
<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import Card from "@/Components/ui/Card.vue";
import { Link } from '@inertiajs/vue3';
</script>
