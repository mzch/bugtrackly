<template>
    <Card>
        <div class="d-flex align-items-center text-secondary mb-3">
            <Avatar :user="response.user" class="bordered me-1"/>
            <span class="fw-semibold me-1">{{ response.user.full_name }}</span>
        </div>
        <p v-if="!editingResponse" v-html="format_text(response.content)"></p>
        <template v-else>
            <FormField class="form-floating">
                <TextArea
                    id="bug_desc"
                    placeholder="Description"
                    v-model.trim="form.content"
                    required
                    style="height: 150px"
                    :class="{'is-invalid' :form.errors.content}"/>
                <InputLabel for="bug_desc" value="Description"/>
                <InputError :message="form.errors.content"/>
            </FormField>
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
        <p class="text-sm text-secondary mb-0">
            {{ formatDate(response.created_at, "d MMMM yyyy à HH'h'mm") }}</p>
        <template #cardFooter v-if="hasCardFooter">
            <SecondaryButton class="btn-sm"
                             :disabled="editingResponse"
                             @click="updateResponseHandler"
                             v-if="canUpdateResponse">Modifier la note</SecondaryButton>
            <DangerButton class="btn-sm ms-2"
                          :disabled="editingResponse"
                          @click="store.commit('bug/setBugResponseToDelete', response)"
                          v-if="canDeleteSingleResponse">Supprimer cette note</DangerButton>
        </template>
    </Card>
</template>

<script setup>

import Card from "@/Components/ui/Card.vue";
import {formatDate} from "@/Helpers/date.js";
import Avatar from "@/Components/ui/user/avatar.vue";
import {computed, ref} from "vue";
import {hasRole} from "@/Helpers/users.js";
import {router, useForm, usePage} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/ui/form/SecondaryButton.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import TextArea from "@/Components/ui/form/TextArea.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import {forEach} from "lodash";
import {format_text} from "@/Helpers/bug.js";
import DangerButton from "@/Components/ui/form/DangerButton.vue";
import {useStore} from "vuex";

const store = useStore()
const props = defineProps({
    response:{
        type:Object,
        required:true,
    }
})
const form = useForm({
    content:props.response.content,
})
const editingResponse = ref(false);

const updateResponseHandler = () => editingResponse.value = true


const cancelEditingResponseHandler = () => {
    editingResponse.value = false;
    form.reset();
}
const submitEditResponseHandler = () => {
    const urlParams = route().params;
    axios.put(route('projects.bug.update-response', [urlParams.project, urlParams.bug, props.response.id]), {
        content: form.content,
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
    return canUpdateResponse.value
})
</script>
