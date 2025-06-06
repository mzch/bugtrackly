<template>
    <Card :card-title="trans('ticket.notes.title')">
        <div>
            <BugSingleResponse v-for="(response, index) in bugResponses" :key="response.id"
                               class="mb-4" :class="{'my-card': response.user?.id === current_user.id}"
                               :response="response"/>


            <Card class="my-card" :class="{'disabled-card' : editing_bug_part !== false}">
                <div class="d-flex align-items-center text-secondary mb-3">
                    <Avatar :user="current_user" class="bordered me-1"/>
                    <span class="fw-semibold me-1">{{ current_user.full_name }}</span>
                </div>
                <div class="row">
                    <div class="col-md-7 d-flex flex-column">
                        <FormField class="form-floating flex-grow-1 mb-2" no-margin-bottom>
                        <TextArea
                            id="bug_desc"
                            :placeholder="trans('ticket.notes.new_note_label')"
                            v-model.trim="form.content"
                            style="height: 100%; min-height: 200px"
                            :class="{'is-invalid' :form.errors.content}"/>
                            <InputLabel for="bug_desc" :value="trans('ticket.notes.new_note_label')"/>
                            <InputError :message="form.errors.content"/>
                        </FormField>
                    </div>
                    <div class="col-md-5">
                        <BugUploadFiles ref="file_uploader" v-model="form.files" :authorize-paste-when-editing="false"/>
                    </div>
                </div>

                <div
                    class="mt-3"
                    v-if="user_can_change_bug_statut || user_can_change_bug_priority || user_can_change_bug_assigned_user">
                    <button v-if="!show_change_bug_props_form" @click="show_change_bug_props_form=true" type="button"
                            class="btn btn-link px-0 text-sm">{{ change_bug_info_label }}
                    </button>
                    <button v-else type="button" @click="cancel_show_change_bug_props_form"
                            class="btn btn-link px-0 text-sm">{{ trans('ticket.notes.cancel_change') }}
                    </button>
                    <TransitionExpand>
                        <div v-if="show_change_bug_props_form">
                            <div class="row g-2">
                                <div class="col-12 col-sm-6 col-xl-3">
                                    <FormField class="form-floating mt-4">
                                        <FormSelect id="new_bug_cat"
                                                    :display-select-label-option="false"
                                                    :select-label="trans('ticket.form.select_new_cat')"
                                                    :options="ticket_categories_options"
                                                    :disabled="!user_can_change_bug_ticket_cat"
                                                    :class="{'is-invalid' :form.errors.ticket_category_id}"
                                                    v-model="form.ticket_category_id"/>
                                        <InputLabel for="new_bug_cat" :value="trans('ticket.form.select_new_cat')"/>
                                        <InputError :message="form.errors.ticket_category_id"/>
                                    </FormField>
                                </div>
                                <div class="col-12 col-sm-6 col-xl-3">
                                    <FormField class="form-floating mt-4">
                                        <FormSelect :select-label="trans('ticket.form.select_new_status')"
                                                    :options="bug_status_options"
                                                    :disabled="!user_can_change_bug_statut"
                                                    :class="{'is-invalid' :form.errors.status}"
                                                    v-model="form.status"/>
                                        <InputLabel for="new_bug_status" :value="trans('ticket.form.new_status')"/>
                                        <InputError :message="form.errors.status"/>
                                    </FormField>
                                </div>
                                <div class="col-12 col-sm-6 col-xl-3">
                                    <FormField class="form-floating mt-4">
                                        <FormSelect :select-label="trans('ticket.form.select_new_priority')"
                                                    :options="bug_priorities_options"
                                                    :class="{'is-invalid' :form.errors.priority}"
                                                    v-model="form.priority"/>
                                        <InputLabel for="new_bug_status" :value="trans('ticket.form.new_priority')"/>
                                        <InputError :message="form.errors.priority"/>
                                    </FormField>
                                </div>
                                <div class="col-12 col-sm-6 col-xl-3">
                                    <UserAvatarVSelect id="assigned_user"
                                                       :label="trans('ticket.form.assign_user')"
                                                       v-model="form.assigned_user_id"
                                                       :show-admin-badge="false"
                                                       :disabled="!user_can_change_bug_assigned_user"
                                                       :users="project.users"/>

                                </div>
                            </div>
                        </div>
                    </TransitionExpand>
                </div>
                <div>
                    <PrimaryButton class="btn-sm"
                                   :disabled="!form.isDirty || form.processing"
                                   @click="submitResponseHandler">{{trans('ticket.notes.add_my_note')}}
                    </PrimaryButton>
                </div>
            </Card>


        </div>
    </Card>
</template>

<script setup>
import Card from "@/Components/ui/Card.vue";
import BugSingleResponse from "@/Pages/Bug/partial/BugSingleResponse.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import TextArea from "@/Components/ui/form/TextArea.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {computed, ref, watch} from "vue";
import {forEach, map} from "lodash";
import UserAvatarVSelect from "@/Components/ui/user/UserAvatarVSelect.vue";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import {getStatusObject} from "@/Helpers/bug.js";
import TransitionExpand from "@/Components/transitions/TransitionExpand.vue";
import {hasRole} from "@/Helpers/users.js";
import BugUploadFiles from "@/Pages/Bug/partial/BugUploadFiles.vue";
import {useStore} from "vuex";
import {trans} from "@/Helpers/translations.js";

const store = useStore()
const props = defineProps({
    bugResponses: {
        type: Array,
        required: true,
        defaults: []
    }
})
const editing_bug_part = computed(()=> store.getters['bug/editingBug'])
const file_uploader = ref(null);
const show_change_bug_props_form = ref(false);
const cancel_show_change_bug_props_form = () => {
    form.defaults({
        content: '',
        priority: bug.value.priority,
        status: bug.value.status,
        assigned_user_id: bug.value.assigned_user_id,
        ticket_category_id: bug.value.ticket_category_id,
    })
    form.isDirty = false;
    form.reset();
    form.clearErrors();
    show_change_bug_props_form.value = false;
}
const bug = computed(() => usePage().props.bug)
const bug_priorities = usePage().props.bug_priorities ?? [];
const bug_priorities_options = computed(() => (
    bug_priorities.map(p => {
        return {
            id: p.id,
            label: p.label
        }
    })
))
const project = computed(() => usePage().props.project)
const project_has_ticket_categories = computed(() => {
    return project.value.ticket_categories.length > 0
})
const current_user = computed(() => usePage().props.auth.user)
const form = useForm({
    content: "",
    priority: bug.value.priority,
    status: bug.value.status,
    assigned_user_id: bug.value.assigned_user_id,
    files:[],
    ticket_category_id:bug.value.ticket_category_id,
})

const user_can_change_bug_statut = computed(() => {
    return hasRole('admin') || [5, 6].includes(bug.value.status) || [5, 6].includes(form.status);
})
const user_can_change_bug_priority = computed(() => {
    return true;
})
const user_can_change_bug_assigned_user = computed(() => {
    return hasRole('admin') || [1, 7].includes(bug.value.status) || [1, 7].includes(form.status);
})
const user_can_change_bug_ticket_cat = computed(() => {
    return hasRole('admin') ;
})

const change_bug_info_label = computed(() => {
    const label_start = trans('ticket.notes.change_note_start'),
        label_change_category = trans('ticket.notes.change_note_category'),
        label_change_status = trans('ticket.notes.change_note_status'),
        label_change_priority = trans('ticket.notes.change_note_priority'),
        label_change_assigned_user = trans('ticket.notes.change_note_assigned_user'),
        label_change_glue = trans('ticket.notes.change_note_glue'),
        label_change_end = trans('ticket.notes.change_note_end');
    let label_parts = [];
    if (user_can_change_bug_statut.value) {
        label_parts.push(label_change_status)
    }
    if(project_has_ticket_categories.value){
        label_parts.push(label_change_category)
    }
    if (user_can_change_bug_priority.value) {
        label_parts.push(label_change_priority)
    }
    if (user_can_change_bug_assigned_user.value) {
        label_parts.push(label_change_assigned_user)
    }
    let formattedString = label_parts.length > 1
        ? label_parts.slice(0, -1).join(", ") + label_change_glue + label_parts[label_parts.length - 1]
        : label_parts[0] || ""; // Gestion des cas où le tableau est vide ou contient un seul élément
    if (!user_can_change_bug_assigned_user.value) {
        formattedString += ' ' + label_change_end
    }
    return `${label_start} ${formattedString}`;
});


const current_bug_status = computed(() => getStatusObject(bug.value.status))
const bug_status_options = computed(() => {
    if (hasRole(('admin'))) {
        const all_status = usePage().props.bug_status || [];
        return all_status.map(p => ({id: p.id, label: p.label}));
    } else {
        const current = {id: current_bug_status.value.id, label: current_bug_status.value.label};
        const available = current_bug_status.value.children.map(status => {
            return {
                id: status.id,
                label: status.label
            }
        })
        return [current, ...available]
    }
})

const ticket_categories_options = computed(() => {
    const defaults = map(project.value.ticket_categories, cat => ({id:cat.id, label:cat.name})) || []
    return [{id:null, label:trans('ticket_cat_none')}, ...defaults]
})

const submitResponseHandler = () => {
    const urlParams = route().params;
    const formDataToSend = new FormData();
    formDataToSend.append('content', form.content);
    formDataToSend.append('priority', form.priority);
    formDataToSend.append('ticket_category_id', form.ticket_category_id=== null ? '' : form.ticket_category_id);
    formDataToSend.append('status', form.status);
    formDataToSend.append('assigned_user_id', form.assigned_user_id === null ? '' : form.assigned_user_id);

    if(form.files.length){
        form.files.forEach((file, index) => {
            formDataToSend.append('files[]', file);
        });
    }else{
        formDataToSend.append('files', '');
    }

    axios.post(route('projects.bug.store-response', [urlParams.project, urlParams.bug]), formDataToSend, {
        headers:{
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            router.reload({
                only: ['bug'],
                onSuccess: () => {
                    cancel_show_change_bug_props_form();
                    file_uploader.value.resetFileInput();
                },
            })
        })
        .catch((er) => {
            console.log("ii");
            forEach(er.response.data.errors, (value, key) => form.setError(key, value[0]))
        })
}

watch(() => form.status, (newBugStatus) => {
    const canUpdateAssignUserWithThisNewStatus = hasRole('admin') || [1, 7].includes(newBugStatus);
    if (!canUpdateAssignUserWithThisNewStatus) {
        form.assigned_user_id = bug.value.assigned_user_id
    }
})
</script>

<style scoped>

</style>
