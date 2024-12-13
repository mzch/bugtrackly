<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import TextInput from '@/Components/ui/form/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FormField from "@/Components/ui/form/FormField.vue";
import FormEnd from "@/Components/ui/form/FormEnd.vue";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <form @submit.prevent="submit">
            <FormField>
                <InputLabel for="email" value="Email :" />

                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    :class="{'is-invalid' :form.errors.email}"
                    autocomplete="username"
                />

                <InputError  :message="form.errors.email" />
            </FormField>

            <FormField>
                <InputLabel for="password" value="Mot de passe :" />

                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    :class="{'is-invalid' :form.errors.password}"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" />
            </FormField>

            <FormField>
                <InputLabel
                    for="password_confirmation"
                    value="Confirmation :"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    :class="{'is-invalid' :form.errors.password_confirmation}"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation"
                />
            </FormField>

            <FormEnd>
                <PrimaryButton
                    class="flex-grow-1"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Réinitialiser mon mot de passe
                </PrimaryButton>
            </FormEnd>
        </form>
    </GuestLayout>
</template>
