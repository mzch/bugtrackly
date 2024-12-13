<template>
    <NewAuthenticatedLayout page-title="Utilisateurs">
        <template #header>Création d'un utilisateur</template>
        <template #headerActions>
            <Link :href="route('settings.users.index')" class="btn btn-primary btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour aux utilisateurs
            </Link>
        </template>
        <Card card-title="Identité de l'utilisateur">

        </Card>
    </NewAuthenticatedLayout>
</template>
<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';
import {ArrowLeftIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
import {generatePassword} from "@/Helpers/users.js";

const props = defineProps({
    user:{
        type:Object,
        required:true,
    }
})
const form = useForm({
    first_name: null,
    last_name: null,
    email: null,
    role_id: "",
    password: generatePassword(),
    photo:null,
});
</script>
