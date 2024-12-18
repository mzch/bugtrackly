<template>
    <AuthenticatedLayout page-title="Mon profil">
        <Card>
            <div class="row gx-5 mb-5">
                <div class="col-lg-6 col-xxl-8">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"/>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <UpdateAvatarForm/>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <UpdatePasswordForm class="mb-5"/>
                    <DeleteUserForm/>
                </div>
            </div>
        </Card>
        <template #header>Mon profil</template>


    </AuthenticatedLayout>
</template>
<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/ui/Card.vue";
import UpdateAvatarForm from "@/Pages/Profile/Partials/UpdateAvatarForm.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

</script>
