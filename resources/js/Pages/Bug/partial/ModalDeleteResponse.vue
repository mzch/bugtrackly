<template>
    <Modal id="suppress_bug_response" :show="showModal" @close="closeModal">
        <template #title>
            <strong>Supprimer la note</strong>
        </template>
        <template #content>
            <form @submit.prevent="submitHandler">
                <div class="modal-body">
                    <p class="mb-0">
                        Voulez vous vraiment supprimer cette note ?

                    </p>
                </div>
                <div class="modal-footer">
                    <SecondaryButton outlined @click="closeModal">Annuler</SecondaryButton>
                    <DangerButton type="submit" :disabled="form_processing">Supprimer la note</DangerButton>
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
import DangerButton from "@/Components/ui/form/DangerButton.vue";
import {router} from "@inertiajs/vue3";

const store = useStore();
const bugResponseToDelete = computed(() => store.getters['bug/bugResponseToDelete']);
const showModal = computed(() => bugResponseToDelete.value !== null);
const closeModal = () => {
    store.commit('bug/setBugResponseToDelete', null)
};

const form_processing = ref(false);
const submitHandler = () => {
    form_processing.value = true;
    const urlParams = route().params;
    axios.delete(route('projects.bug.delete-response', [urlParams.project, urlParams.bug, bugResponseToDelete.value.id]))
        .then(response => {
            router.reload({
                only: ['bug'],
                onSuccess:() => form.reset(),
            })
        })
        .catch((er) => {
            console.error(er);
        })
        .finally(() => {
            form_processing.value = false;
            closeModal();
        })
}
</script>

<style scoped>

</style>
