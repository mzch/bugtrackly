<template>
    <AuthenticatedLayout :page-title="`${project.name} - ${trans('project.show.report_label')}`">
        <template #header>{{ project.name }} -&nbsp;<span class="text-secondary">{{ trans('pageTitle.create_ticket') }}</span></template>
        <template #headerActions>
            <Link :href="route('projects.show', project.slug)" class="btn btn-primary btn-with-icon btn-sm">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour
            </Link>
        </template>
        <FormCard :card-title="trans('project.show.report_label')" :submit-handler-fn-callback="createBugHandler">
            <div class="row">
                <div class="col-md-7 d-flex flex-column">
                    <FormField class="form-floating">
                        <TextInput
                            id="bug_title"
                            class="form-control-lg"
                            :placeholder="trans('ticket.form.title_label')"
                            v-model.trim="form.title"
                            type="text"
                            autofocus
                            required
                            maxlength="255"
                            :class="{'is-invalid' :form.errors.title}"
                        />
                        <InputLabel for="bug_title" :value="trans('ticket.form.title_label')"/>
                        <InputError :message="form.errors.title"/>
                    </FormField>
                    <FormField class="form-floating flex-grow-1">
                        <TextArea
                            id="bug_desc"
                            :placeholder="trans('ticket.form.desc_label')"
                            v-model.trim="form.content"
                            required
                            style="height: 100%; min-height: 200px"
                            :class="{'is-invalid' :form.errors.content}"/>
                        <InputLabel for="bug_desc" :value="trans('ticket.form.desc_label')"/>
                        <InputError :message="form.errors.content"/>
                    </FormField>
                </div>
                <div class="col-md-5 mb-3">
                    <BugUploadFiles v-model="form.files"/>
                </div>
            </div>

            <div class="row">
                <div :class="nb_col_infos_bug">
                    <FormField class="form-floating mt-4">
                        <FormSelect id="bug_cat"
                                    :display-select-label-option="false"
                                    :select-label="trans('ticket.form.category_label')"
                                    :options="ticket_categories_options"
                                    :class="{'is-invalid' :form.errors.ticket_category_id}"
                                    v-model="form.ticket_category_id"/>
                        <InputLabel for="new_bug_cat" :value="trans('ticket.form.category_label')"/>
                        <InputError :message="form.errors.ticket_category_id"/>
                    </FormField>
                </div>
                <div :class="nb_col_infos_bug" v-if="hasRole('admin')">
                    <FormField class="form-floating mt-4">
                        <FormSelect id="bug_status" :options="status_options" v-model.number="form.status"/>
                        <InputLabel for="bug_status" :value="trans('ticket.form.status_label')"/>
                        <InputError :message="form.errors.status"/>
                    </FormField>
                </div>
                <div :class="nb_col_infos_bug">
                    <FormField class="form-floating mt-4">
                        <FormSelect id="bug_priority" :options="priorities_options" v-model.number="form.priority"/>
                        <InputLabel for="bug_priority" :value="trans('ticket.form.priotity_label')"/>
                        <InputError :message="form.errors.priority"/>
                    </FormField>
                </div>
                <div :class="nb_col_infos_bug">
                    <UserAvatarVSelect id="vs-assigned-user" :label="trans('ticket.form.assign_user')" :users="project.users" v-model="form.assigned_user_id"></UserAvatarVSelect>
                </div>
            </div>
            <template #cardFooter>
                <div class="d-flex align-items-center justify-content-end">
                    <PrimaryButton type="submit" :disabled="form.processing || !form.isDirty">{{ trans('general.save_action') }}</PrimaryButton>
                </div>
            </template>
        </FormCard>
    </AuthenticatedLayout>
</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {ArrowLeftIcon} from "@heroicons/vue/24/outline/index.js";
import FormCard from "@/Components/ui/FormCard.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import {computed} from "vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import TextArea from "@/Components/ui/form/TextArea.vue";
import UserAvatarVSelect from "@/Components/ui/user/UserAvatarVSelect.vue";
import {hasRole} from "@/Helpers/users.js";
import BugUploadFiles from "@/Pages/Bug/partial/BugUploadFiles.vue";
import {trans} from "../../Helpers/translations.js";
import {map} from "lodash";

const props = defineProps({
    project:{
        type:Object,
        required:true,
    },
    bug_priorities:{
        type:Array,
        required:true,
    },
    bug_status:{
        type:Array,
        required:true,
    }
})
const form = useForm({
    assigned_user_id:null,
    title:"",
    content:"",
    status:1,
    priority:3,
    ticket_category_id:null,
    files:[],
})

const priorities_options = computed(() => {
    const priorities = props.bug_priorities || [];
    return priorities.map(p => ({id: p.id, label: p.label}));
});

const status_options = computed(() => {
    const status = props.bug_status || [];
    return status.map(p => ({id: p.id, label: p.label}));
});

const ticket_categories_options = computed(() => {
    const defaults = map(props.project.ticket_categories, cat => ({id:cat.id, label:cat.name})) || []
    return [{id:null, label:trans('ticket_cat_none')}, ...defaults]
})

const nb_col_infos_bug = computed(() => hasRole('admin') ? "col-12 col-sm-6 col-xl-3" : "col-12 col-md-6 col-xl-4");

const createBugHandler = () => {
    form.post(route('projects.bug.store', props.project.slug), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
        },
    });
}
</script>
