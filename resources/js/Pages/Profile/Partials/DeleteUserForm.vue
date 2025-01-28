<script setup>
import DangerButton from '@/Components/ui/form/DangerButton.vue';
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import Modal from '@/Components/ui/Modal.vue';
import SecondaryButton from '@/Components/ui/form/SecondaryButton.vue';
import TextInput from '@/Components/ui/form/TextInput.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';
import FormField from "@/Components/ui/form/FormField.vue";
import Card from "@/Components/ui/Card.vue";
import {trans} from "@/Helpers/translations.js";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const user = usePage().props.auth.user;
const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
    // setTimeout(() => passwordInput.value.focus(), 300)
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <Card :card-title="trans('profile.delete_account.title' )">
        <p class="text-secondary">{{trans('profile.delete_account.info')}}</p>
        <DangerButton @click="confirmUserDeletion">{{ trans('profile.delete_account.button') }}</DangerButton>
        <Modal id="deleteAccountModal" :show="confirmingUserDeletion" @close="closeModal">
            <template #title>
                <strong>{{trans('profile.delete_account.modal.title')}}</strong>
            </template>
            <template #content>
                <div class="modal-body">
                    <p>{{trans('profile.delete_account.modal.content')}}</p>
                    <form @submit.prevent="deleteUser">
                        <TextInput type="text" v-model="user.name" autocomplete="name" class="visually-hidden" readonly
                                   id="username"/>
                        <FormField class="form-floating">
                            <TextInput
                                id="password"
                                type="password"
                                :placeholder="trans('profile.delete_account.modal.password')"
                                ref="passwordInput"
                                v-model="form.password"
                                required
                                :class="{'is-invalid' :form.errors.password}"
                                autocomplete="current-password"
                            />
                            <InputLabel
                                for="password"
                                :value="trans('profile.delete_account.modal.password')"
                                class="sr-only"
                            />
                            <InputError :message="form.errors.password"/>
                        </FormField>


                        <div class="d-flex align-items-center justify-content-end">
                            <SecondaryButton @click="closeModal">
                                {{ trans('general.cancel_action') }}
                            </SecondaryButton>

                            <DangerButton
                                type="submit"
                                class="ms-2"
                                :disabled="form.processing"
                                @click="deleteUser"
                            >
                                {{ trans('profile.delete_account.modal.button') }}
                            </DangerButton>
                        </div>
                    </form>
                </div>
            </template>
        </Modal>
    </Card>
</template>
