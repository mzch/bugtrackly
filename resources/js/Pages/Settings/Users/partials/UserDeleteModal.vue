<template>
    <pre>userToDelete:{{itemToDelete}}</pre>
    <Modal id="suppress_modal" :show="showSuppressModal" @close="closeSupressModal">
        <template #title>
            <strong>Suppression d'un utilisateur</strong>
        </template>
        <template #content>
            <form @submit.prevent="submitDeleteHandler">
                <div class="modal-body">
                    <p class="mb-0">
                        Voulez vous vraiment supprimer l'utilisateur <strong v-if="itemToDelete">{{ userToDelete.full_name }}</strong> ?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="closeSupressModal" class="btn btn-secondary">Non</button>
                    <button type="submit" :disabled="formDeleteProcessing" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<script setup>

import Modal from "@/Components/ui/Modal.vue";
import {useStore} from "vuex";
import {computed, ref, watch} from "vue";
import {router} from "@inertiajs/vue3";

const store = useStore();
const itemToDelete = computed(() => store.getters['usersManagement/userToDelete']);

const userToDelete = ref(null);
const formDeleteProcessing = ref(false);
const showSuppressModal = computed( () => itemToDelete.value !== null )
const closeSupressModal = () => store.commit('usersManagement/setUserToDelete', null);

const submitDeleteHandler = () => {
    formDeleteProcessing.value = true;
    router.delete(route('settings.users.destroy', itemToDelete.value.id), {
        onSuccess: page => {
            closeSupressModal();
        },
        onFinish:visit => {
            formDeleteProcessing.value = false
        }
    })
}


watch(itemToDelete, (newValue, oldValue) => {
    if (newValue) {
        userToDelete.value = newValue
    }
});
</script>
