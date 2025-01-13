<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import TextInput from '@/Components/ui/form/TextInput.vue';
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
            <FormField class="form-floating">
                <TextInput
                    id="email"
                    type="email"
                    placeholder="Adresse email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    :class="{'is-invalid' :form.errors.email}"
                />
                <InputLabel for="email" value="Identifiant ou adresse e-mail" />
                <InputError :message="form.errors.email" />
            </FormField>
            <FormField class="form-floating">
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="Mot de passe"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    :class="{'is-invalid' :form.errors.password}"
                />
                <InputLabel for="password" value="Mot de passe" />
                <InputError :message="form.errors.password" />
            </FormField>
            <FormField>
                <FormCheck id="loginRemeberMe" label="Se souvenir de moi" v-model:checked="form.remember"/>
            </FormField>

            <FormEnd>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')">
                    Mot de passe oublié ?</Link>

                <PrimaryButton type="submit"
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
