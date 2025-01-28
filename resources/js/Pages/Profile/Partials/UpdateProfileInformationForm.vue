<script setup>
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import TextInput from '@/Components/ui/form/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import FormField from "@/Components/ui/form/FormField.vue";
import {computed, nextTick} from "vue";
import Card from "@/Components/ui/Card.vue";
import FormCard from "@/Components/ui/FormCard.vue";
import {trans} from "@/Helpers/translations.js";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = computed(() => usePage().props.auth.user)

const form = useForm({
    first_name: user.value.first_name,
    last_name: user.value.last_name,
    email: user.value.email,
});

const submitFormHandler = () => {
    form.patch(route('profile.update'), {
        onSuccess: () => {
            nextTick(()=>form.reset())

        },
    })
}
</script>

<template>
    <FormCard :submit-handler-fn-callback="submitFormHandler" :card-title="trans('profile.profil_info.title')">
        <p class="text-secondary">
            {{trans('profile.profil_info.desc')}}
        </p>
            <FormField class="form-floating">
                <TextInput
                    id="first_name"
                    type="text"
                    :placeholder="trans('profile.profil_info.first_name')"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="name"
                    :class="{'is-invalid' :form.errors.first_name}"/>
                <InputLabel for="first_name" :value="trans('profile.profil_info.first_name')" />
                <InputError :message="form.errors.first_name" />
            </FormField>

            <FormField class="form-floating">
                <TextInput
                    id="last_name"
                    type="text"
                    :placeholder="trans('profile.profil_info.last_name')"
                    v-model="form.last_name"
                    required
                    autofocus
                    autocomplete="name"
                    :class="{'is-invalid' :form.errors.last_name}"/>
                <InputLabel for="name" :value="trans('profile.profil_info.last_name')" />
                <InputError :message="form.errors.last_name" />
            </FormField>

            <FormField no-margin-bottom class="form-floating">
                <TextInput
                    id="email"
                    type="email"
                    :placeholder="trans('profile.profil_info.email')"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :class="{'is-invalid' :form.errors.email}"/>
                <InputLabel for="email" :value="trans('profile.profil_info.email')" />
                <InputError :message="form.errors.email" />
            </FormField>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 mb-0 text-sm text-warning">
                    <strong>{{ trans('profile.profil_info.warning') }}</strong>, {{trans('profile.profil_info.email_not_verified')}}
                    <Link
                        type="button"
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="btn btn-outline-primary btn-sm"
                    >
                        {{ trans('profile.profil_info.verify') }}
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-1 mb-2 text-success"
                >
                    {{ trans('profile.profil_info.verify_msg') }}
                </div>
            </div>
        <template #cardFooter>
            <div class="d-flex align-items-center justify-content-between">
                <PrimaryButton type="submit" :disabled="form.processing || !form.isDirty">{{ trans('general.save_action') }}</PrimaryButton>
                <Transition
                    enter-active-class="transition-opacity"
                    enter-from-class="opacity-0"
                    leave-active-class="transition-opacity"
                    leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="mb-0 text-success fw-bold">
                        {{ trans('profile.profil_info.success') }}
                    </p>
                </Transition>
            </div>
        </template>
    </FormCard>
</template>
