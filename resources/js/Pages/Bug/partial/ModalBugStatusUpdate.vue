<template>
    <Modal id="suppress_project_modal" :show="showModal" @close="closeModal">
        <template #title>
            <strong>Modifier le statut</strong>
        </template>
        <template #content>
            <form @submit.prevent="submitHandler">
                <div class="modal-body">
                    <FormField class="form-floating">
                        <FormSelect autofocus
                                    select-label="Sélectionner un nouveau statut"
                                    :options="bug_status_options"
                                    :class="{'is-invalid' :form.errors.status}"
                                    v-model="form.status"/>
                        <InputLabel for="new_bug_status" value="Nouveau statut pour le bug en cours"/>
                        <InputError :message="form.errors.status"/>
                    </FormField>
                </div>
                <div class="modal-footer">
                    <SecondaryButton outlined @click="closeModal">Annuler</SecondaryButton>
                    <PrimaryButton type="submit" :disabled="!form.isDirty || form.processing">Modifier le statut
                    </PrimaryButton>
                </div>
            </form>
        </template>
    </Modal>
</template>

<script setup>

import Modal from "@/Components/ui/Modal.vue";
import {computed, ref} from "vue";
import {useStore} from "vuex";
import SecondaryButton from "@/Components/ui/form/SecondaryButton.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import {getStatusObject} from "@/Helpers/bug.js";
import { forEach} from "lodash";

const store = useStore();
const closeModal = () => {
    form.reset();
    form.clearErrors();
    store.commit('bug/setBugToUpdateStatus', null)
};

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    bug: {
        type: Object,
        required: true,
    },
})
const current_bug_status = computed(() => getStatusObject(props.bug.status))
const bug_status_options = computed(() => (
    current_bug_status.value.children.map(status => {
        return {
            id: status.id,
            label: status.label
        }
    })
))

const bugToUpdateStatus = computed(() => store.getters['bug/bugToUpdateStatus']);
const showModal = computed(() => bugToUpdateStatus.value !== null);

const form = useForm({
    status: null
})

const submitHandler = () => {
    axios.post(route('projects.bug.update-status', [props.project.slug, props.bug.id]), {
        status: form.status,
    })
        .then(response => {
            closeModal();
            router.reload({only: ['bug']})
        })
        .catch((er) => {
            forEach(er.response.data.errors, (value, key) =>  form.setError(key, value[0]))
        })

}
</script>
