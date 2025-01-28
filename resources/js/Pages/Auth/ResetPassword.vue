<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import TextInput from '@/Components/ui/form/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FormField from "@/Components/ui/form/FormField.vue";
import FormEnd from "@/Components/ui/form/FormEnd.vue";
import {trans} from "@/Helpers/translations.js";

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
        <Head :title="trans('auth.reset_pwd_title')" />

        <form @submit.prevent="submit">
            <FormField class="form-floating">
                <TextInput
                    id="email"
                    type="email"
                    :placeholder="trans('auth.email_address')"
                    v-model="form.email"
                    required
                    autofocus
                    :class="{'is-invalid' :form.errors.email}"
                    autocomplete="username"
                />
                <InputLabel for="email" :value="trans('auth.email_address')" />
                <InputError  :message="form.errors.email" />
            </FormField>

            <FormField class="form-floating">
                <TextInput
                    id="password"
                    type="password"
                    :placeholder="trans('auth.password' )"
                    v-model="form.password"
                    required
                    :class="{'is-invalid' :form.errors.password}"
                    autocomplete="new-password"
                />
                <InputLabel for="password" :value="trans('auth.password')" />
                <InputError :message="form.errors.password" />
            </FormField>

            <FormField class="form-floating">
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    :placeholder="trans('auth.password_confirmation')"
                    v-model="form.password_confirmation"
                    required
                    :class="{'is-invalid' :form.errors.password_confirmation}"
                    autocomplete="new-password"
                />
                <InputLabel for="password_confirmation" :value="trans('auth.password_confirmation')"/>
                <InputError :message="form.errors.password_confirmation"
                />
            </FormField>

            <FormEnd>
                <PrimaryButton
                    type="submit"
                    class="flex-grow-1"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ trans('auth.forgot_pwd_submit_label') }}
                </PrimaryButton>
            </FormEnd>
        </form>
    </GuestLayout>
</template>
