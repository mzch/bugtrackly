<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import TextInput from '@/Components/ui/form/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormField from "@/Components/ui/form/FormField.vue";

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <FormField class="form-floating">
                <TextInput
                    id="first_name"
                    type="text"
                    placeholder="Prénom"
                    :class="{'is-invalid' : form.errors.first_name}"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="first_name"
                />
                <InputLabel for="first_name" value="Prénom" />
                <InputError :message="form.errors.first_name" />
            </FormField>
            <FormField class="form-floating">
                <TextInput
                    id="last_name"
                    type="text"
                    placeholder="Nom"
                    :class="{'is-invalid' : form.errors.last_name}"
                    v-model="form.last_name"
                    required
                    autocomplete="last_name"
                />
                <InputLabel for="last_name" value="Nom" />
                <InputError :message="form.errors.last_name" />
            </FormField>
            <FormField class="form-floating">
                <TextInput
                    id="email"
                    type="email"
                    placeholder="Adresse email"
                    v-model="form.email"
                    :class="{'is-invalid' : form.errors.email}"
                    required
                    autocomplete="username"
                />
                <InputLabel for="email" value="Adresse email" />
                <InputError :message="form.errors.email" />
            </FormField>
            <FormField class="form-floating">
                <TextInput
                    id="password"
                    type="password"
                    placeholder="Mot de passe"
                    :class="{'is-invalid' : form.errors.password}"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputLabel for="password" value="Mot de passe" />
                <InputError :message="form.errors.password" />
            </FormField>
            <FormField class="form-floating">
                <TextInput
                    id="password_confirmation"
                    type="password"
                    placeholder="Confirmation du mot de passe"
                    :class="{'is-invalid' : form.errors.password_confirmation}"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputLabel for="password_confirmation" value="Confirmation du mot de passe" />
                <InputError :message="form.errors.password_confirmation"/>
            </FormField>

            <div class="d-flex align-items-center justify-content-between">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Déjà inscrit ?
                </Link>

                <PrimaryButton
                    type="submit"
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
