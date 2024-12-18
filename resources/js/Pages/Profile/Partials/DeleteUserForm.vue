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

    <p class="text-secondary">
        Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
        Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez
        conserver.
    </p>

    <DangerButton @click="confirmUserDeletion">Supprimer mon compte</DangerButton>

    <Modal id="deleteAccountModal" :show="confirmingUserDeletion" @close="closeModal">
        <template #title>
            <strong>Êtes-vous sûr de vouloir supprimer votre compte ?</strong>
        </template>
        <template #content>
            <div class="modal-body">
                <p>
                    Une fois que votre compte est supprimé, toutes ses ressources et données
                    seront définitivement supprimées. Veuillez saisir votre mot de passe pour
                    confirmer que vous souhaitez supprimer définitivement votre compte.
                </p>
                <form @submit.prevent="deleteUser">

                    <TextInput type="text" v-model="user.name" autocomplete="name" class="visually-hidden" readonly
                               id="username"/>
                    <FormField class="form-floating">
                        <TextInput
                            id="password"
                            type="password"
                            placeholder="Votre mot de passe"
                            ref="passwordInput"
                            v-model="form.password"
                            required
                            :class="{'is-invalid' :form.errors.password}"
                            autocomplete="current-password"
                        />
                        <InputLabel
                            for="password"
                            value="Votre mot de passe"
                            class="sr-only"
                        />
                        <InputError :message="form.errors.password"/>
                    </FormField>


                    <div class="d-flex align-items-center justify-content-end">
                        <SecondaryButton @click="closeModal">
                            Annuler
                        </SecondaryButton>

                        <DangerButton
                            type="submit"
                            class="ms-2"
                            :disabled="form.processing"
                            @click="deleteUser"
                        >
                            Oui, supprimez mon compte !
                        </DangerButton>
                    </div>
                </form>
            </div>
        </template>
    </Modal>
</template>
