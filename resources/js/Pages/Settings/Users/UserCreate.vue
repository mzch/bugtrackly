<template>
    <AuthenticatedLayout :page-title="trans('settings.users.create.title')">
        <template #header><span v-html="dynamic_page_title"/></template>
        <template #headerActions>
            <Link :href="route('settings.users.index')" class="btn btn-primary btn-sm btn-with-icon">
                <ArrowLeftIcon class="size-1 me-1"/>
                {{trans('settings.users.edit.back')}}
            </Link>
        </template>
        <FormCard :submit-handler-fn-callback="createUserFormHandler">
            <template #cardFooter>
                <div class="d-flex justify-content-end">
                    <Link :href="route('settings.users.index')" class="btn btn-light btn-with-icon me-2">
                        {{trans('general.back_action')}}
                    </Link>
                    <PrimaryButton type="submit"
                                   class="btn-with-icon"
                                   :disabled="submitButtonDisabled">
                        {{trans('general.save_action')}}
                    </PrimaryButton>
                </div>
            </template>
            <div class="row gx-5">
                <div class="col-lg-6 col-xxl-8">
                    <UserIdentity :form="form"/>
                    <Card :card-title="trans('settings.users.manage.security_title')">
                        <FormField class="form-floating">
                            <TextInput v-model="form.password"
                                       type="text"
                                       :placeholder="trans('settings.users.security.accounpwd')"
                                       id="password"
                                       minlength="8"
                                       :class="{'is-invalid':form.errors.password}"/>
                            <InputLabel for="password" :value="trans('settings.users.security.accounpwd')" required/>
                            <InputError :message="form.errors.password"/>
                        </FormField>
                        <button type="button" class="btn btn-secondary mb-2" @click="generateNewPassword">
                            {{trans('settings.users.security.generate_new_pwd')}}
                        </button>
                        <FormCheck id="sendPassword"
                                   :label="trans('settings.users.security.send_firstpwd')"
                                   :is-invalid="form.errors.send_password"
                                   v-model:checked="form.send_password"/>
                    </Card>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <UserAvatar :form="form" :user="fakeUser"  class="mb-5"/>
                    <UserRole :form="form"/>
                </div>
            </div>
        </FormCard>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';
import {ArrowLeftIcon, XCircleIcon, ArchiveBoxArrowDownIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import {generateInitials, generatePassword} from "@/Helpers/users.js";
import InputError from "@/Components/ui/form/InputError.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import FormCheck from "@/Components/ui/form/FormCheck.vue";
import {computed} from "vue";
import FormField from "@/Components/ui/form/FormField.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import FormCard from "@/Components/ui/FormCard.vue";
import UserIdentity from "@/Pages/Settings/Users/partials/form/UserIdentity.vue";
import UserRole from "@/Pages/Settings/Users/partials/form/UserRole.vue";
import UserAvatar from "@/Pages/Settings/Users/partials/form/UserAvatar.vue";
import {trans} from "@/Helpers/translations.js";

const props = defineProps({
    roles: {
        type: Array,
        required: true,
    }
})
const dynamic_page_title = computed(() => {
    const start = trans('settings.users.create.title'),
        firstname = form.first_name ? form.first_name.toString().trim() : '',
        lastname = form.last_name ? form.last_name.toString().trim() : '';
    return (firstname === "" && lastname === "")
        ? start
        : `${start} : <span class="text-secondary">${firstname} ${lastname}</span>`
})

const submitButtonDisabled = computed(() => form.processing || !form.isDirty);

const form = useForm({
    first_name: null,
    last_name: null,
    email: null,
    role_id: 2,
    password: generatePassword(),
    send_password: true,
    photo: null,
});
const fakeUser = computed(() => {
    const firstname = form.first_name ? form.first_name.toString().trim() : '',
        lastname = form.last_name ? form.last_name.toString().trim() : '';
    return {
        initiales: generateInitials(`${firstname} ${lastname}`),
        email: form.email
    }
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
