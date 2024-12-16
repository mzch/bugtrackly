<template>
    <NewAuthenticatedLayout page-title="Utilisateurs">
        <template #header><span v-html="dynamic_page_title"/></template>
        <template #headerActions>
            <Link :href="route('settings.users.index')" class="btn btn-primary btn-sm btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour aux utilisateurs
            </Link>
        </template>
        <FormCard :submit-handler-fn-callback="createUserFormHandler">
            <pre>{{ form }}</pre>
            <template #cardFooter>
                <div class="d-flex justify-content-end">
                    <Link :href="route('settings.users.index')" class="btn btn-light btn-with-icon me-2">
                        <XCircleIcon class="size-1 me-1 "/>
                        Retour light
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
                    <Card card-title="Identité de l'utilisateur" class="mb-5">
                        <FormField class="form-floating">
                            <TextInput v-model.trim="form.first_name"
                                       type="text"
                                       placeholder="Prénom"
                                       id="first_name"
                                       :class="{'is-invalid':form.errors.first_name}"
                                       autofocus
                            />
                            <InputLabel for="first_name" value="Prénom" required/>
                            <InputError :message="form.errors.first_name"/>
                        </FormField>
                        <FormField class="form-floating">
                            <TextInput v-model.trim="form.last_name"
                                       type="text"
                                       placeholder="Nom"
                                       id="last_name"
                                       :class="{'is-invalid':form.errors.last_name}"/>
                            <InputLabel for="last_name" value="Nom" required/>
                            <InputError :message="form.errors.last_name"/>
                        </FormField>
                        <FormField class="form-floating">
                            <TextInput v-model.trim="form.email"
                                       type="email"
                                       placeholder="Adresse email"
                                       id="email"
                                       :class="{'is-invalid':form.errors.email}"/>
                            <InputLabel for="email" value="Email" required/>
                            <InputError :message="form.errors.email"/>
                        </FormField>
                    </Card>
                    <Card card-title="Sécurité">
                        <FormField class="form-floating">
                            <TextInput v-model="form.password"
                                       type="text"
                                       placeholder="Password"
                                       id="password"
                                       :class="{'is-invalid':form.errors.password}"/>
                            <InputLabel for="password" value="Mot de passe du compte" required/>
                            <InputError :message="form.errors.password"/>
                        </FormField>
                        <button class="btn btn-secondary mb-2" @click="generateNewPassword">Générer un autre mot de
                            passe
                        </button>
                        <FormCheck id="loginRemeberMe"
                                   label="Envoyer un e-mail à la personne à propos de son nouveau compte."
                                   :is-invalid="form.errors.send_password"
                                   v-model:checked="form.send_password"/>
                    </Card>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <Card card-title="Rôle" class="mb-5">
                        <FormField class="form-floating">
                            <FormSelect id="user-role"
                                        :display-select-label-option="false"
                                        :options="roles_options"
                                        v-model.number="form.role_id"/>
                            <InputLabel for="user-role" value="Sélectionnez un rôle" required/>
                            <InputError :message="form.errors.role_id"/>
                        </FormField>
                    </Card>
                    <Card card-title="Photo de profil">
                        <p>Photo</p>
                    </Card>
                </div>
            </div>
        </FormCard>

    </NewAuthenticatedLayout>
</template>
<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';
import {ArrowLeftIcon, XCircleIcon, ArchiveBoxArrowDownIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import {generatePassword} from "@/Helpers/users.js";
import InputError from "@/Components/ui/form/InputError.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import FormCheck from "@/Components/ui/form/FormCheck.vue";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import {computed} from "vue";
import FormField from "@/Components/ui/form/FormField.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import FormCard from "@/Components/ui/FormCard.vue";

const props = defineProps({
    roles: {
        type: Array,
        required: true,
    }
})
const roles_options = computed(() => props.roles.map(r => ({id: r.id, label: r.name})));
const dynamic_page_title = computed(() => {
    const start = 'Création d\'un utilisateur';
    return (form.first_name === "" && form.last_name === "")
        ? start
        : `${start} : <span class="text-secondary">${form.first_name} ${form.last_name}</span>`
})

const submitButtonDisabled = computed(() => form.processing || !form.isDirty);

const form = useForm({
    first_name: "",
    last_name: "",
    email: "",
    role_id: 2,
    password: generatePassword(),
    send_password: true,
    photo: null,
});

const generateNewPassword = () => form.password = generatePassword();

const createUserFormHandler = () => {
    form.post(route('settings.users.store'), {
        onSuccess: () => {
            form.reset()
        },
        onError: () => {
            console.error("oups");
        }
    })
}


</script>
