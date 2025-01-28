<template>
    <Modal id="suppress_project_modal" :show="showSuppressModal" @close="closeSupressModal">
        <template #title>
            <strong>{{ trans('settings.projects.delete_title') }}</strong>
        </template>
        <template #content>
            <form @submit.prevent="submitDeleteHandler">
                <div class="modal-body">
                    <p class="mb-0">
                        {{ trans('settings.projects.delete_desc') }} <strong v-if="projectToDelete">{{ projectToDelete.name }}</strong> ?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="closeSupressModal" class="btn btn-secondary">
                        {{trans('general.no_action')}}
                    </button>
                    <button type="submit" :disabled="formDeleteProcessing" class="btn btn-danger">
                        {{trans('general.delete_action')}}
                    </button>
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
import {trans} from "../../../../Helpers/translations.js";
const store = useStore();
const itemToDelete = computed(() => store.getters['projectsManagement/projectToDelete']);
const projectToDelete = ref(null);
const formDeleteProcessing = ref(false);
const showSuppressModal = computed( () => itemToDelete.value !== null )
const closeSupressModal = () => store.commit('projectsManagement/setProjectToDelete', null);

const submitDeleteHandler = () => {
    formDeleteProcessing.value = true;
    router.delete(route('settings.projects.destroy', itemToDelete.value.id), {
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
        projectToDelete.value = newValue
    }
});
</script>

