

<template>
    <NewAuthenticatedLayout page-title="Mon profil">
        <FormCard :submit-handler-fn-callback="updateProfileFormHandler">
            <div class="row gx-5">
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
            </div>

        </FormCard>
    <template #header>Mon profil</template>

    <Card>
        <DeleteUserForm/>
    </Card>
    </NewAuthenticatedLayout>
</template>
<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

import NewAuthenticatedLayout from "@/Layouts/NewAuthenticatedLayout.vue";
import Card from "@/Components/ui/Card.vue";
import FormCard from "@/Components/ui/FormCard.vue";
import {ArchiveBoxArrowDownIcon, XCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const updateProfileFormHandler = () => {
    console.log("updateProfileFormHandler");
}
</script>
