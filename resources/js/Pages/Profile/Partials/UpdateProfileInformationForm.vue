<script setup>
import InputError from '@/Components/ui/form/InputError.vue';
import InputLabel from '@/Components/ui/form/InputLabel.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import TextInput from '@/Components/ui/form/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import FormField from "@/Components/ui/form/FormField.vue";
import {computed, nextTick} from "vue";

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
        <p class="text-secondary">
            Mettez à jour les informations de profil et l'adresse électronique de votre compte.
        </p>
        <form
            @submit.prevent="submitFormHandler"
            class="mt-6 space-y-6"
        >
            <FormField class="form-floating">
                <TextInput
                    id="first_name"
                    type="text"
                    placeholder="Votre prénom"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="name"
                    :class="{'is-invalid' :form.errors.first_name}"/>
                <InputLabel for="first_name" value="Votre prénom" />
                <InputError :message="form.errors.first_name" />
            </FormField>

            <FormField class="form-floating">
                <TextInput
                    id="last_name"
                    type="text"
                    placeholder="Votre nom"
                    v-model="form.last_name"
                    required
                    autofocus
                    autocomplete="name"
                    :class="{'is-invalid' :form.errors.last_name}"/>
                <InputLabel for="name" value="Votre nom" />
                <InputError :message="form.errors.last_name" />
            </FormField>

            <FormField class="form-floating">
                <TextInput
                    id="email"
                    type="email"
                    placeholder="Votre adresse email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :class="{'is-invalid' :form.errors.email}"/>
                <InputLabel for="email" value="Votre adresse email" />
                <InputError :message="form.errors.email" />
            </FormField>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="mb-3">
                <p class="mt-2 mb-0 text-sm text-warning">
                    <strong>Attention</strong>, votre adresse électronique n'est pas vérifiée.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="btn btn-outline-primary btn-sm"
                    >
                        Vérifier mon adresse email
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-1 mb-2 text-success"
                >
                    Un nouveau lien de vérification a été envoyé à votre adresse électronique.
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <PrimaryButton type="submit" :disabled="form.processing || !form.isDirty">Enregistrer</PrimaryButton>
                <Transition
                    enter-active-class="transition-opacity"
                    enter-from-class="opacity-0"
                    leave-active-class="transition-opacity"
                    leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="mb-0 text-success fw-bold">
                        Informations enregistrées !
                    </p>
                </Transition>
            </div>
        </form>
</template>
