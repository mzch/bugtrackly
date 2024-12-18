<template>
        <p class="text-secondary">
            Veillez à ce que votre compte utilise un mot de passe long et aléatoire pour rester
            sécurisé.
        </p>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <FormField class="form-floating">
                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    placeholder="Mot de passe actuel"
                    v-model="form.current_password"
                    type="password"
                    autocomplete="current-password"
                    required
                    minlength="8"
                    :class="{'is-invalid' :form.errors.current_password}"
                />
                <InputLabel for="current_password" value="Mot de passe actuel" />
                <InputError :message="form.errors.current_password"/>
            </FormField>

            <FormField class="form-floating">
                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    placeholder="Nouveau mot de passe"
                    required
                    minlength="8"
                    :class="{'is-invalid' :form.errors.password}"
                    autocomplete="new-password"
                />
                <InputLabel for="password" value="Nouveau mot de passe" />
                <InputError :message="form.errors.password" />
            </FormField>

            <FormField class="form-floating">
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="Confirmer le mot de passe"
                    required
                    minlength="8"
                    :class="{'is-invalid' :form.errors.password_confirmation}"
                    autocomplete="new-password"
                />
                <InputLabel for="password_confirmation" value="Confirmer le mot de passe"/>
                <InputError :message="form.errors.password_confirmation"/>
            </FormField>
            <div class="d-flex align-items-center justify-content-between">
                <PrimaryButton type="submit" :disabled="form.processing || !form.isDirty">Enregistrer</PrimaryButton>
                <Transition
                    enter-active-class="transition-opacity ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition-opacity ease-in-out"
                    leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="mb-0 text-success fw-bold">
                        Votre nouveau mot de passe a été pris en compte.
                    </p>
                </Transition>
            </div>
        </form>
</template>
<script setup>
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import TextInput from '@/Components/ui/form/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import FormField from "@/Components/ui/form/FormField.vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>
