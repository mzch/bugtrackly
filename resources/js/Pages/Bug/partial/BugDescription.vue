<template>
<Card :card-title="card_title" :card-icon="card_icon" card-icon-class="text-status-in_progress" class="mb-4">
    <template #cardHeaderAction>
        <div class="col-auto ms-auto">
            <BadgeTicketCategory class="me-1" :bug="bug" v-if="projects_has_tickets_categories"/>
            <BagdeStatusBug class="me-1" :bug="bug"/>
            <BadgePriorityBug :extended-label="true" :bug="bug"/>
        </div>
    </template>
    <div class="text-secondary mb-3" v-if="bug.user">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <Avatar :user="bug.user" class="bordered me-2 "/>
                <div class="d-flex flex-column">
                    <span class="fw-semibold me-1">{{ bug.user.full_name }}</span>
                    <span class="text-sm text-secondary mb-0 opacity-75">{{ formatDate(bug.created_at, trans('general.date-with-hour')) }}</span>
                </div>
            </div>
            <div v-if="canModifyBug || canModifyBug" :style="{pointerEvents:!editing_bug_description ? 'auto' : 'none'}" class="position-relative z-3" @mouseenter="showBugSubMenuHandler" @mouseleave="hideBugSubMenuHandler">
                <button class="btn btn-link  btn-sm btn-with-icon px-1 text-secondary"
                        :disabled="editing_bug_description"
                        >
                    <EllipsisVerticalIcon class="size-1"/>
                </button>


                <div v-show="show_bug_submenu" class="actions_bug">
                    <SecondaryButton type="button" :disabled="editing_bug_description" class="btn-sm me-2 w-100"
                                     @click="click_edit_bug_handler" v-if="canModifyBug">
                        {{trans('ticket.show.update_label')}}
                    </SecondaryButton>
                    <DangerButton :disabled="editing_bug_description" class="btn-sm w-100 mt-1"
                                  v-if="canDeleteBug"
                                  @click="click_delete_bug_handler">
                        {{trans('ticket.show.delete_label')}}
                    </DangerButton>
                </div>
            </div>
        </div>

    </div>
    <MarkdownRenderer v-if="!editing_bug_description" :markdown="first_bug_comment.content"/>
    <template v-else>
        <div class="row">
            <div class="col-md-7 d-flex flex-column">
                <FormField class="form-floating">
                    <TextInput v-model="form.title"
                               class="form-control-lg"
                               :class="{'is-invalid' :form.errors.title}"
                               :placeholder="trans('ticket.form.title_label')"
                               autofocus
                               required
                               maxlength="255"/>
                    <InputLabel for="bug_title" :value="trans('ticket.form.title_label')"/>
                    <InputError :message="form.errors.title"/>
                </FormField>
                <FormField class="form-floating">
                    <TextArea
                        id="bug_desc"
                        :placeholder="trans('ticket.form.desc_label')"
                        v-model.trim="form.content"
                        required
                        style="height: 200px"
                        :class="{'is-invalid' :form.errors.content}"/>
                    <InputLabel for="bug_desc" :value="trans('ticket.form.desc_label')"/>
                    <InputError :message="form.errors.content"/>
                </FormField>
            </div>
            <div class="col-md-5">
                <BugUploadFiles ref="file_uploader" v-model="form.files" authorize-paste-when-editing="description"/>
            </div>
        </div>

        <div class="mb-3">
            <SecondaryButton class="btn-sm me-2"
                             type="button"
                             outlined
                             @click="cancelEditingBugHandler">
                {{ trans('general.cancel_action') }}
            </SecondaryButton>
            <PrimaryButton class="btn-sm"
                           :disabled="!form.isDirty || form.processing"
                           @click="editBugHandler">{{ trans('general.save_action') }}</PrimaryButton>
        </div>
    </template>
    <template #cardFooter v-if="show_card_footer">
        <div class="row">
            <div class="col" v-if="first_bug_comment.files.length">
                <RelatedFiles  :comment="first_bug_comment"/>
            </div>
            <div class="col" :class="{'border-start':first_bug_comment.files.length}" v-if="bug.user_followers.length">
                <BugFollowers :followers="bug.user_followers"/>
            </div>
            <div class="col" :class="{'border-start':bug.user_followers.length}" v-if="bug.assigned_user">
                <BugAssignedUser :user="bug.assigned_user"/>
            </div>
        </div>

    </template>
</Card>
</template>

<script setup>

import Card from "@/Components/ui/Card.vue";
import {computed, ref, watch} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import BadgePriorityBug from "@/Components/ui/bug/BadgePriorityBug.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import TextArea from "@/Components/ui/form/TextArea.vue";
import SecondaryButton from "@/Components/ui/form/SecondaryButton.vue";
import {forEach} from "lodash";
import {formatDate} from "@/Helpers/date.js";
import {useStore} from "vuex";
import {hasRole} from "@/Helpers/users.js";
import {EllipsisVerticalIcon} from "@heroicons/vue/24/solid/index.js";
import {StarIcon} from "@heroicons/vue/24/solid/index.js";
import DangerButton from "@/Components/ui/form/DangerButton.vue";
import MarkdownRenderer from "@/Components/ui/MarkdownRenderer.vue";
import RelatedFiles from "@/Components/ui/bug/RelatedFiles.vue";
import BugUploadFiles from "@/Pages/Bug/partial/BugUploadFiles.vue";
import BagdeStatusBug from "@/Components/ui/bug/BagdeStatusBug.vue";
import BugFollowers from "@/Components/ui/bug/BugFollowers.vue";
import UserAvatar from "@/Pages/Settings/Users/partials/form/UserAvatar.vue";
import BugAssignedUser from "@/Components/ui/bug/BugAssignedUser.vue";
import {trans} from "../../../Helpers/translations.js";
import BadgeTicketCategory from "@/Components/ui/bug/BadgeTicketCategory.vue";
const store = useStore();
const editing_bug_part = computed(()=> store.getters['bug/editingBug'])
const props = defineProps({
    bug: {
        type: Object,
        required: true,
    },
    project: {
        type: Object,
        required: true,
    },
    isFollowing: {
        type: Boolean,
        required: true,
    }
})
const projects_has_tickets_categories = computed(() => props.project.ticket_categories.length > 0)
const show_bug_submenu = ref(false);
let timer = null;
const showBugSubMenuHandler = () => {
    clearTimeout(timer);
    show_bug_submenu.value = true;
}
const hideBugSubMenuHandler = () => {
    clearTimeout(timer);
    timer = setTimeout(() => show_bug_submenu.value = false, 200);
}
const first_bug_comment = computed(() => props.bug.bug_comments[0] ?? false);

const show_card_footer = computed(() => {
    return first_bug_comment.value.files.length || props.bug.user_followers.length || props.bug.assigned_user
})

const form = useForm({
    title: props.bug.title,
    content: first_bug_comment.value.content,
    files:[],
})

const editing_bug_description = ref(false);


const click_edit_bug_handler = () => {
    editing_bug_description.value = true;
    show_bug_submenu.value = false;
    store.commit('bug/setEditingBug', 'description');
}

const click_delete_bug_handler = () => {
    store.commit('bug/setBugToDelete', props.bug)
    show_bug_submenu.value = false;
}

const card_title = computed(() => {
    if(!editing_bug_description){
        return props.bug.title;
    }else{
        return form.title;
    }
})

const card_icon = computed(() => {
    if(props.isFollowing){
        return StarIcon;
    }
    return null;
})

const canModifyBug = computed(() => hasRole('admin'));
const canDeleteBug = computed(() => hasRole('admin'));
const file_uploader = ref(null);
const cancelEditingBugHandler = () => {
    store.commit('bug/setEditingBug', false);
    editing_bug_description.value = false;
    form.reset();
    form.clearErrors();
}
const editBugHandler = () => {
    const formDataToSend = new FormData();
    formDataToSend.append('title', form.title);
    formDataToSend.append('content', form.content);
    if(form.files.length){
        form.files.forEach((file, index) => {
            formDataToSend.append('files[]', file);
        });
    }else{
        formDataToSend.append('files', '');
    }

    axios.post(route('projects.bug.update', [props.project.slug, props.bug.id]), formDataToSend, {
        headers:{
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            router.reload({
                only: ['bug'],
                onSuccess:() => {

                    cancelEditingBugHandler();
                    form.defaults({
                        title: props.bug.title,
                        content: first_bug_comment.value.content,
                    })
                    form.reset();
                    file_uploader.value.resetFileInput();
                },
            })
        })
        .catch((er) => {
            forEach(er.response.data.errors, (value, key) =>  form.setError(key, value[0]))
        })
}
watch(editing_bug_part , newValue => {
    if(newValue !== 'description' && editing_bug_description.value){
        editing_bug_description.value = false;
        form.reset();
    }
})
</script>
