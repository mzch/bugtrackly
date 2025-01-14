<template>
    <AuthenticatedLayout page-title="Gestions des projets">
        <template #header><span v-html="dynamic_page_title"/></template>
        <template #headerActions>
            <Link :href="route('settings.projects.index')" class="btn btn-primary btn-sm btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour aux projets
            </Link>
        </template>
        <FormCard :submit-handler-fn-callback="updateHandler">
            <template #cardFooter>
                <div class="d-flex justify-content-end">
                    <Link :href="route('settings.projects.index')" class="btn btn-light btn-with-icon me-2">
                        Retour
                    </Link>
                    <PrimaryButton type="submit" :disabled="submitButtonDisabled">
                        Enregistrer
                    </PrimaryButton>
                </div>
            </template>
            <div class="row gx-5">
                <div class="col-12">
                    <ProjectIdentity :form="form" class="mb-4"/>
                    <ProjectUsers :form="form"/>
                </div>
            </div>
        </FormCard>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {ArrowLeftIcon} from "@heroicons/vue/24/outline/index.js";
import {Link, useForm} from "@inertiajs/vue3";
import FormCard from "@/Components/ui/FormCard.vue";
import ProjectIdentity from "@/Pages/Settings/Projects/partials/form/ProjectIdentity.vue";
import {computed} from "vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import ProjectUsers from "@/Pages/Settings/Projects/partials/form/ProjectUsers.vue";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    }
})
const users_for_project = computed(() => props.project.users.map(user => user.id))
const form = useForm({
    name: props.project.name,
    slug: props.project.slug,
    short_desc: props.project.short_desc,
    photo: null,
    users:users_for_project.value,
    delete_old_photo: false,
});

const submitButtonDisabled = computed(() => form.processing || !form.isDirty);
const dynamic_page_title = computed(() => {
    const start = 'Édition d\'un projet',
        name = form.name ? form.name.toString().trim() : '';
    return (name === "")
        ? start
        : `${start} : <span class="text-secondary">${name}</span>`
})

const updateHandler = () => {
    console.log("Update du projet");
    form.post(route('settings.projects.update', props.project.id)), {
        forceFormData: true,
        onSuccess: () => {
            console.log("ok");
        },
        onFinish: () => console.log("ok"),
    }
}
</script>
