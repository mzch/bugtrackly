

<template>
    <AuthenticatedLayout page-title="Mon profil">
        <Card>
            <div class="row gx-5 mb-5">
                <div class="col-lg-6 col-xxl-8">
                    <Card card-title="Informations sur le profil" class="mb-5">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </Card>
                    <Card card-title="Mettre à jour votre mot de passe">
                        <UpdatePasswordForm/>
                    </Card>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    avatar
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <Card card-title="Suppression de mon compte">
                        <DeleteUserForm/>
                    </Card>
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

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

</script>
