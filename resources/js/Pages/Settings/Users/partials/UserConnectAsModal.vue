<template>
    <Modal id="connectAs_modal" :show="showModal" @close="closeModal">
        <template #title>
            <strong>{{ trans( 'settings.users.connect_as') }} <span v-if="userToConnectAs">{{userToConnectAs.full_name}}</span></strong>
        </template>
        <template #content>
            <form @submit.prevent="submitModal">
                <div class="modal-body">
                    {{ trans('settings.users.connect_as_desc') }}  <strong v-if="userToConnectAs">{{userToConnectAs.full_name}}</strong>.
                </div>
                <div class="modal-footer">
                    <button type="button" @click="closeModal" class="btn btn-secondary">{{ trans('general.no_action' ) }}</button>
                    <button type="submit" :disabled="formProcessing" class="btn btn-danger">{{ trans('settings.users.go_connect') }}</button>
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
import {trans} from "../../../../Helpers/translations.js";

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
