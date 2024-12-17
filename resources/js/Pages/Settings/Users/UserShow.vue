<template>
    <NewAuthenticatedLayout page-title="Utilisateurs">
        <template #header><span v-html="dynamic_page_title"/></template>
        <template #headerActions>
            <Link :href="route('settings.users.index')" class="btn btn-primary btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour aux utilisateurs
            </Link>
        </template>
        <FormCard :submit-handler-fn-callback="updateUserFormHandler">
            <template #cardFooter>
                <div class="d-flex justify-content-end">
                    <Link :href="route('settings.users.index')" class="btn btn-light btn-with-icon me-2">
                        <XCircleIcon class="size-1 me-1 "/>
                        Retour
                    </Link>
                    <PrimaryButton type="submit"
                                   class="btn-with-icon"
                                   :disabled="submitButtonDisabled">
                        <ArchiveBoxArrowDownIcon class="size-1 me-1"/>
                        Enregistrer
                    </PrimaryButton>
                </div>
            </template>
            <pre>{{form}}</pre>
            <div class="row gx-5">
                <div class="col-lg-6 col-xxl-8">
                    <UserIdentity :form="form"/>
                    <Card card-title="Sécurité">

                    </Card>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <UserRole :form="form" class="mb-5"/>
                </div>
            </div>

        </FormCard>
    </NewAuthenticatedLayout>
</template>
<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';
import {ArchiveBoxArrowDownIcon, ArrowLeftIcon, XCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
import {computed} from "vue";
import FormCard from "@/Components/ui/FormCard.vue";
import UserIdentity from "@/Pages/Settings/Users/partials/form/UserIdentity.vue";
import UserRole from "@/Pages/Settings/Users/partials/form/UserRole.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";

const props = defineProps({
    user:{
        type:Object,
        required:true,
    }
})
const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    role_id: props.user.role.id,
    photo:null,
});
const dynamic_page_title = computed(() => {
    const start = 'Édition d\'un utilisateur',
        firstname = form.first_name ? form.first_name.toString().trim() : '',
        lastname = form.last_name ? form.last_name.toString().trim() : '';
    return (firstname === "" && lastname === "")
        ? start
        : `${start} : <span class="text-secondary">${firstname} ${lastname}</span>`
})
const submitButtonDisabled = computed(() => form.processing || !form.isDirty);
const updateUserFormHandler= () => {
    console.log("updateUserFormHandler");
}
</script>
