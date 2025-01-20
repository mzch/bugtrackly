<template>
    <Card>
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center text-secondary mb-3">
                <Avatar :user="response.user" class="bordered me-2"/>
                <div class="d-flex flex-column">
                    <span class="fw-semibold me-1">{{ response.user?.full_name || "Utilisateur supprimé" }}</span>
                    <span class="text-sm text-secondary mb-0 opacity-75">{{ formatDate(response.created_at, "d MMMM yyyy à HH'h'mm") }}</span>
                </div>

            </div>

            <div v-if="hasCardFooter" :style="{pointerEvents:!editingResponse ? 'auto' : 'none'}" class="position-relative z-3" @mouseenter="showBugSubMenuHandler" @mouseleave="hideBugSubMenuHandler">
                <button class="btn btn-link  btn-sm btn-with-icon px-1 text-secondary"
                        :disabled="editingResponse"
                >
                    <EllipsisVerticalIcon class="size-1"/>
                </button>

                <div v-show="show_bug_submenu" class="actions_bug">
                    <SecondaryButton class="btn-sm w-100"
                                     :disabled="editingResponse"
                                     @click="updateResponseHandler"
                                     v-if="canUpdateResponse">Modifier la note</SecondaryButton>
                    <DangerButton class="btn-sm mt-1 w-100"
                                  :disabled="editingResponse"
                                  @click="store.commit('bug/setBugResponseToDelete', response)"
                                  v-if="canDeleteSingleResponse">Supprimer la note</DangerButton>
                </div>
            </div>
        </div>

        <MarkdownRenderer v-if="!editingResponse" :markdown="response.content"/>
        <template v-else>
        <div class="row">
            <div class="col-md-7 d-flex flex-column">
                <FormField class="form-floating flex-grow-1 mb-2">
                    <TextArea
                        id="bug_desc"
                        placeholder="Description"
                        v-model.trim="form.content"
                        required
                        style="height: 100%; min-height: 200px"
                        :class="{'is-invalid' :form.errors.content}"/>
                    <InputLabel for="bug_desc" value="Description"/>
                    <InputError :message="form.errors.content"/>
                </FormField>
            </div>
            <div class="col-md-5">
                <BugUploadFiles ref="file_uploader" v-model="form.files" :authorize-paste-when-editing="`comment_${response.id}`"/>
            </div>
        </div>
        <div class="mb-3">
            <SecondaryButton class="btn-sm me-2"
                             type="button"
                             outlined
                             @click="cancelEditingResponseHandler">
                Annuler
            </SecondaryButton>
            <PrimaryButton class="btn-sm"
                           :disabled="!form.isDirty || form.processing"
                           @click="submitEditResponseHandler">Valider</PrimaryButton>
        </div>
        </template>
        <template #cardFooter v-if="response.files.length">
            <RelatedFiles :comment="response"/>
        </template>
    </Card>
</template>

<script setup>

import Card from "@/Components/ui/Card.vue";
import {formatDate} from "@/Helpers/date.js";
import Avatar from "@/Components/ui/user/avatar.vue";
import {computed, ref, watch} from "vue";
import {hasRole} from "@/Helpers/users.js";
import {router, useForm, usePage} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/ui/form/SecondaryButton.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import TextArea from "@/Components/ui/form/TextArea.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import {forEach} from "lodash";
import DangerButton from "@/Components/ui/form/DangerButton.vue";
import {useStore} from "vuex";
import {EllipsisVerticalIcon} from "@heroicons/vue/24/solid/index.js";
import MarkdownRenderer from "@/Components/ui/MarkdownRenderer.vue";
import RelatedFiles from "@/Components/ui/bug/RelatedFiles.vue";
import BugUploadFiles from "@/Pages/Bug/partial/BugUploadFiles.vue";

const store = useStore()
const props = defineProps({
    response:{
        type:Object,
        required:true,
    }
})
const file_uploader = ref(null);
const form = useForm({
    content:props.response.content,
    files:[],
})
const show_bug_submenu = ref(false);
let timer = null;
const showBugSubMenuHandler = () => {
    clearTimeout(timer);
    timer = null;
    show_bug_submenu.value = true;
}
const hideBugSubMenuHandler = () => {
    clearTimeout(timer);
    timer = null;
    timer = setTimeout(() => show_bug_submenu.value = false, 200);
}

const editingResponse = ref(false);

const updateResponseHandler = () => {
    editingResponse.value = true,
    store.commit('bug/setEditingBug', `comment_${props.response.id}`)
}


const cancelEditingResponseHandler = () => {
    editingResponse.value = false;
    form.reset();
    form.clearErrors();
    store.commit('bug/setEditingBug', false)
}
const submitEditResponseHandler = () => {
    const urlParams = route().params;
    const formDataToSend = new FormData();
    formDataToSend.append('content', form.content);
    if(form.files.length){
        form.files.forEach((file, index) => {
            formDataToSend.append('files[]', file);
        });
    }else{
        formDataToSend.append('files', '');
    }

    axios.post(route('projects.bug.update-response', [urlParams.project, urlParams.bug, props.response.id]),formDataToSend, {
        headers:{
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            router.reload({
                only: ['bug'],
                onSuccess:() => {

                    cancelEditingResponseHandler();
                    form.defaults({
                        content: props.response.content,
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
/**
 * L'utilisateur en cours peut-il modifier la réponse ?
 * @type {ComputedRef<boolean>}
 */
const canUpdateResponse = computed(() => {
    // un admin voir toujours ce bouton
    if (hasRole('admin')) {
        return true;

    }
    return  false
});

const canDeleteSingleResponse = computed(() => hasRole('admin'))

/**
 * Des boutons en footer sont-il visible ?
 * @type {ComputedRef<T>}
 */
const hasCardFooter = computed(() => {
    return canUpdateResponse.value || canDeleteSingleResponse.value
})
const editing_bug_part = computed(()=> store.getters['bug/editingBug'])
watch(editing_bug_part , newValue => {
    if(newValue !== `comment_${props.response.id}` && editingResponse.value){
        editingResponse.value = false;
        form.reset();
    }
})
</script>
