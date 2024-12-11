<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

import NewAuthenticatedLayout from "@/Layouts/NewAuthenticatedLayout.vue";
import Card from "@/Components/ui/Card.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <NewAuthenticatedLayout page-title="Mon profil">
        {{route().current()}}
        <br>
        {{$page.component}}
    <template #header>Mon profil</template>

    <Card class="mb-4">
        <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
        />
    </Card>


    <Card class="mb-4">
        <UpdatePasswordForm/>
    </Card>

    <Card>
        <DeleteUserForm/>
    </Card>
    </NewAuthenticatedLayout>
</template>
