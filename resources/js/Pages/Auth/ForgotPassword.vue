<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import TextInput from '@/Components/ui/form/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FormField from "@/Components/ui/form/FormField.vue";
import FormEnd from "@/Components/ui/form/FormEnd.vue";
import AlertSuccess from "@/Components/ui/AlertSuccess.vue";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <p>
            Vous avez oublié votre mot de passe ?
            Pas de problème. Indiquez-nous votre adresse email et nous vous enverrons un lien
            de réinitialisation du mot de passe.
        </p>

        <AlertSuccess :dismissible="true" v-if="status">{{status}}</AlertSuccess>

        <form @submit.prevent="submit">
            <FormField>
                <InputLabel for="email" class="visually-hidden" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    :class="{'is-invalid' :form.errors.email}"
                    v-model="form.email"
                    placeholder = "votre adresse email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError  :message="form.errors.email" />
            </FormField>

            <FormEnd>
                <PrimaryButton
                    type="submit"
                    class="flex-grow-1"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Réinitliser mon mot de passe
                </PrimaryButton>
            </FormEnd>
        </form>
    </GuestLayout>
</template>
