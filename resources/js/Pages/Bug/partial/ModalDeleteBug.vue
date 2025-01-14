<template>

    <Modal id="suppress_bug_response" :show="showModal" @close="closeModal">
        <template #title>
            <strong>Supprimer le bug</strong>
        </template>
        <template #content>
            <form @submit.prevent="submitHandler">
                <div class="modal-body">
                    <p class="mb-0">
                        Voulez vous vraiment supprimer ce bug ?<br>
                        <small class="text-secondary">
                            L'ensemble des notes et documents joints à celui-ci seront également supprimés.
                        </small>
                        <pre>{{form}}</pre>
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
import {computed, ref, watch} from "vue";
import {useStore} from "vuex";
import SecondaryButton from "@/Components/ui/form/SecondaryButton.vue";
import DangerButton from "@/Components/ui/form/DangerButton.vue";
import {router, useForm} from "@inertiajs/vue3";
import {notify} from "@kyvg/vue3-notification";

const store = useStore();
const bugToDelete = computed(() => store.getters['bug/bugToDelete']);
const showModal = computed(() => bugToDelete.value !== null);
const closeModal = () => {
    store.commit('bug/setBugToDelete', null)
};

const form = useForm({})

const submitHandler = () => {
    const urlParams = route().params,
    routeDeleteBug = route('projects.bug.destroy', [urlParams.project, urlParams.bug]);
    form.delete(routeDeleteBug);
}
</script>

<style scoped>

</style>
