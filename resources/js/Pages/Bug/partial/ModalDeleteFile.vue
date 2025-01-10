<template>
    <Modal id="suppress_bug_response" :show="showModal" @close="closeModal">
        <template #title>
            <strong>Supprimer le fichier</strong>
        </template>
        <template #content>
            <form @submit.prevent="submitHandler">
                <div class="modal-body">
                    <p class="mb-0">
                        Voulez vous vraiment supprimer ce fichier ?<br>
                    </p>
                </div>
                <div class="modal-footer">
                    <SecondaryButton outlined @click="closeModal">Annuler</SecondaryButton>
                    <DangerButton type="submit" :disabled="form.processing">Supprimer la note</DangerButton>
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
import {router, useForm} from "@inertiajs/vue3";

const store = useStore();
const fileToDelete = computed(() => store.getters['bug/fileToDelete']);
const showModal = computed(() => fileToDelete.value !== null);
const closeModal = () => store.commit('bug/setFileToDelete', null);

const form = useForm({})
const submitHandler = () => {
    const urlParams = route().params,
        deleteRoute = route('projects.bug.destroy_file', [urlParams.project, urlParams.bug, fileToDelete.value.bug_comment_id, fileToDelete.value.id]);
    axios.delete(deleteRoute)
        .then(res => {
            router.reload({
                only: ['bug'],
            })
        })
        .finally(() => closeModal())
}
</script>
