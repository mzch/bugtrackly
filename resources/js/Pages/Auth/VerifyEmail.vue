<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/ui/form/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Vérification de votre adresse -email" />

        <p class="mb-4">
            Avant de poursuivre, vous devez vérifier votre
            adresse électronique en cliquant sur le lien que nous venons de vous envoyer par courrier électronique ?
            <br>
            Si vous n'avez pas reçu cet email, nous vous en enverrons un autre avec plaisir.
        </p>

        <div
            class="mb-4 text-success"
            v-if="verificationLinkSent"
        >
            Un nouveau lien de vérification a été envoyé à l'adresse électronique que vous avez
            fournie.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 d-flex align-items-center justify-content-between">
                <PrimaryButton
                    type="submit"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Renvoyer l'e-mail de vérification
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="btn btn-secondary"
                    >Se déconnecter</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
