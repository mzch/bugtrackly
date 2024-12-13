<template>
    <Modal id="connectAs_modal" :show="showModal" @close="closeModal">
        <template #title>
            <strong>Connexion en tant que <span v-if="userToConnectAs">{{userToConnectAs.full_name}}</span></strong>
        </template>
        <template #content>
            <form @submit.prevent="submitModal">
                <div class="modal-body">
                    Vous vous apprêter à vous connecter en tant que <strong v-if="userToConnectAs">{{userToConnectAs.full_name}}</strong> ?
                </div>
                <div class="modal-footer">
                    <button type="button" @click="closeModal" class="btn btn-secondary">Non</button>
                    <button type="submit" :disabled="formProcessing" class="btn btn-danger">Poursuivre la connexion</button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<script setup>

import {useStore} from "vuex";
import {computed, ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Components/ui/Modal.vue";

const store = useStore();
const itemToConnectAs = computed(() => store.getters['usersManagement/userToConnectAs']);
const showModal = computed( () => itemToConnectAs.value !== null )

const userToConnectAs = ref(null);
const formProcessing = ref(false);

const closeModal = () => store.commit('usersManagement/setUserToConnectAs', null);

const submitModal = () => {
    closeModal();
    router.get(route('settings.users.switch_user', userToConnectAs.value.id))
};

watch(itemToConnectAs, (newValue, oldValue) => {
    if (newValue) {
        userToConnectAs.value = newValue
    }
});

</script>
