<template>
    <AuthenticatedLayout :page-title="trans('settings.projects.create.title')">
        <template #header>{{ trans('settings.projects.create.title') }}</template>
        <template #headerActions>
            <Link :href="route('settings.projects.index')" class="btn btn-primary btn-sm btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                {{ trans('settings.projects.edit.back' ) }}
            </Link>
        </template>
        <FormCard :submit-handler-fn-callback="createProjectFormHandler">
            <template #cardFooter>
                <div class="d-flex justify-content-end">
                    <Link :href="route('settings.projects.index')" class="btn btn-light btn-with-icon me-2">
                        {{ trans('general.back_action') }}
                    </Link>
                    <PrimaryButton type="submit"
                                   class="btn-with-icon"
                                   :disabled="submitButtonDisabled">
                        {{ trans('general.save_action') }}
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
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import {computed} from "vue";
import ProjectIdentity from "@/Pages/Settings/Projects/partials/form/ProjectIdentity.vue";
import {trans} from "@/Helpers/translations.js";
import ProjectUsers from "@/Pages/Settings/Projects/partials/form/ProjectUsers.vue";

const form = useForm({
    name: null,
    slug: null,
    short_desc: null,
    photo: null,
    users:[],
});

const submitButtonDisabled = computed(() => form.processing || !form.isDirty);

const createProjectFormHandler = () => {
    console.log("creation de projets");
    form.post(route('settings.projects.store')), {
        forceFormData:true
    }
}
</script>
