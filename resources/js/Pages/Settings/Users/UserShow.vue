<template>
    <NewAuthenticatedLayout page-title="Utilisateurs">
        <template #header><span v-html="dynamic_page_title"/></template>
        <template #headerActions>
            <Link :href="route('settings.users.index')" class="btn btn-primary btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour aux utilisateurs
            </Link>
        </template>
        <FormCard :submit-handler-fn-callback="updateUserFormHandler">
            <template #cardFooter>
                <div class="d-flex justify-content-end">
                    <Link :href="route('settings.users.index')" class="btn btn-light btn-with-icon me-2">
                        <XCircleIcon class="size-1 me-1 "/>
                        Retour
                    </Link>
                    <PrimaryButton type="submit"
                                   class="btn-with-icon"
                                   :disabled="submitButtonDisabled">
                        <ArchiveBoxArrowDownIcon class="size-1 me-1"/>
                        Enregistrer
                    </PrimaryButton>
                </div>
            </template>
            <div class="row gx-5">
                <div class="col-lg-6 col-xxl-8">
                    <UserIdentity :form="form"/>
                    <Card card-title="Sécurité">
                        <button type="button"
                                class="btn"
                                :class="{'btn-secondary': !showNewPassWordForm, 'btn-outline-secondary':showNewPassWordForm}"
                                @click="generateNewPassword">
                            <span v-if="showNewPassWordForm">Re-générer un nouveau mot de passe</span>
                            <span v-else>Générer un nouveau mot de passe</span>

                        </button>
                        <button type="button"
                                class="btn btn-secondary ms-2"
                                v-if="showNewPassWordForm"
                                @click="form.password = null">
                            Annuler
                        </button>
                        <TransitionExpand>
                            <div v-if="showNewPassWordForm" class="pt-2">
                                <FormField :no-margin-bottom="true" class="form-floating">
                                    <TextInput
                                        id="password"
                                        placeholder="Nouveau mot de passe"
                                        v-model="form.password"
                                        type="text"
                                        minlength="8"
                                        :class="{'is-invalid':form.errors.password}"
                                    />
                                    <InputLabel for="password" value="Nouveau mot de passe" />
                                    <InputError :message="form.errors.password" />
                                </FormField>
                                <div class="form-text">Laissez ce champ vide pour ne pas modifier le mot de passe de l'utilisateur</div>
                                <FormCheck id="sendPassword"
                                           class="mt-3"
                                           label="Envoyer un e-mail à la personne à propos de son changement de mot de passe."
                                           :is-invalid="form.errors.send_new_password"
                                           v-model:checked="form.send_new_password"/>

                            </div>
                        </TransitionExpand>
                    </Card>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <UserAvatar :form="form" :user="fakeUser"  class="mb-5"/>
                    <UserRole :form="form" />
                </div>
            </div>

        </FormCard>
    </NewAuthenticatedLayout>
</template>
<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';
import {ArchiveBoxArrowDownIcon, ArrowLeftIcon, XCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
import {computed, watch} from "vue";
import FormCard from "@/Components/ui/FormCard.vue";
import UserIdentity from "@/Pages/Settings/Users/partials/form/UserIdentity.vue";
import UserRole from "@/Pages/Settings/Users/partials/form/UserRole.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import UserAvatar from "@/Pages/Settings/Users/partials/form/UserAvatar.vue";
import {generateInitials, generatePassword} from "@/Helpers/users.js";
import TransitionExpand from "@/Components/transitions/TransitionExpand.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import FormCheck from "@/Components/ui/form/FormCheck.vue";

const props = defineProps({
    user:{
        type:Object,
        required:true,
    }
})
const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    role_id: props.user.role.id,
    password:null,
    send_new_password:true,
    photo:null,
    delete_old_photo:false,
});
const generateNewPassword = () => form.password = generatePassword();
const showNewPassWordForm = computed(() => {
    return form.password !== null && form.password !== '';
})

const dynamic_page_title = computed(() => {
    const start = 'Édition d\'un utilisateur',
        firstname = form.first_name ? form.first_name.toString().trim() : '',
        lastname = form.last_name ? form.last_name.toString().trim() : '';
    return (firstname === "" && lastname === "")
        ? start
        : `${start} : <span class="text-secondary">${firstname} ${lastname}</span>`
})
const submitButtonDisabled = computed(() => form.processing || !form.isDirty);


const fakeUser = computed(() => {
    const firstname = form.first_name ? form.first_name.toString().trim() : '',
        lastname = form.last_name ? form.last_name.toString().trim() : '';
    return {
        initiales: generateInitials(`${firstname} ${lastname}`),
        email: form.email,
        profile_photo_url: !form.delete_old_photo ? props.user.profile_photo_url: null
    }
});


const updateUserFormHandler= () => {
    form.post(route('settings.users.update', props.user.id), {
        forceFormData:true,
        onSuccess: () => {
            form.reset()
        },
        onError:() => {

        }
    })
}

</script>
