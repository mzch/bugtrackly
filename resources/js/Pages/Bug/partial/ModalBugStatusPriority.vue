<template>
    <Modal id="suppress_project_modal" :show="showModal" @close="closeModal">
        <template #title>
            <strong>Modifier la priorité</strong>
        </template>
        <template #content>
            <form @submit.prevent="submitHandler">
                <div class="modal-body">
                    <FormField class="form-floating">
                        <FormSelect autofocus
                                    select-label="Sélectionner une nouvelle priorité"
                                    :options="bug_priorities_options"
                                    :class="{'is-invalid' :form.errors.priority}"
                                    v-model="form.priority"/>
                        <InputLabel for="new_bug_status" value="Nouvelle priorité"/>
                        <InputError :message="form.errors.priority"/>
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
import {computed} from "vue";
import {useStore} from "vuex";
import SecondaryButton from "@/Components/ui/form/SecondaryButton.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import { forEach} from "lodash";

const store = useStore();
const closeModal = () => {
    form.reset();
    form.clearErrors();
    store.commit('bug/setBugToUpdatePriority', null)
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
const bug_priorities = usePage().props.bug_priorities ?? [];
const bug_priorities_options = computed(() => (
    bug_priorities.map(p => {
        return {
            id: p.id,
            label: props.bug.priority !== p.id ? p.label : `${p.label} (priorité actuelle)`,
            disabled:props.bug.priority === p.id,
        }
    })
))

const bugToUpdatePriority = computed(() => store.getters['bug/bugToUpdatePriority']);
const showModal = computed(() => bugToUpdatePriority.value !== null);

const form = useForm({
    priority: null
})

const submitHandler = () => {
    axios.put(route('projects.bug.update-priority', [props.project.slug, props.bug.id]), {
        priority: form.priority,
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
