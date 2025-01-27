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
import {trans} from "@/Helpers/translations.js";

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
        <Head :title="trans('auth.forgot_pwd_title')" />

        <p>{{trans('auth.forgot_pwd_text')}}</p>

        <AlertSuccess :dismissible="true" v-if="status">{{status}}</AlertSuccess>

        <form @submit.prevent="submit">
            <FormField class="form-floating">
                <TextInput
                    id="email"
                    type="email"
                    :class="{'is-invalid' :form.errors.email}"
                    v-model="form.email"
                    :placeholder="trans('auth.forgot_pwd_email_label')"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputLabel for="email" :value="trans('auth.forgot_pwd_email_label')" />
                <InputError  :message="form.errors.email" />
            </FormField>

            <FormEnd>
                <PrimaryButton
                    type="submit"
                    class="flex-grow-1"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{trans('auth.forgot_pwd_submit_label') }}
                </PrimaryButton>
            </FormEnd>
        </form>
    </GuestLayout>
</template>
