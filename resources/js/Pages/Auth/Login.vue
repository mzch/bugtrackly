<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormCheck from "@/Components/ui/form/FormCheck.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import FormEnd from "@/Components/ui/form/FormEnd.vue";
import AlertSuccess from "@/Components/ui/AlertSuccess.vue";
defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Connexion" />

        <AlertSuccess :dismissible="true" v-if="status">{{status}}</AlertSuccess>

        <form @submit.prevent="submit" class="text-dark text-lg">
            <FormField>
                <InputLabel class="visually-hidden" for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    placeholder="Adresse email"
                    v-model="form.email"
                    autofocus
                    autocomplete="username"
                    :class="{'is-invalid' :form.errors.email}"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </FormField>
            <FormField>
                <InputLabel class="visually-hidden" for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="Mot de passe"
                    v-model="form.password"
                    autocomplete="current-password"
                    :class="{'is-invalid' :form.errors.password}"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </FormField>
            <FormField>
                <FormCheck id="loginRemeberMe" label="Se souvenir de moi" v-model:checked="form.remember"/>
            </FormField>

            <FormEnd>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')">
                    Mot de passe oublié ?</Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Se connecter
                </PrimaryButton>
            </FormEnd>
        </form>
    </GuestLayout>
</template>
