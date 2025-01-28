<template>
    <Card :card-title="trans('settings.users.manage.identity_title')" class="mb-5">
        <FormField class="form-floating">
            <TextInput v-model.trim="form.first_name"
                       type="text"
                       :placeholder="trans('settings.users.manage.first_name')"
                       id="first_name"
                       :class="{'is-invalid':form.errors.first_name}"
                       autofocus
                       required
            />
            <InputLabel for="first_name" :value="trans('settings.users.manage.first_name')" required/>
            <InputError :message="form.errors.first_name"/>
        </FormField>
        <FormField class="form-floating">
            <TextInput v-model.trim="form.last_name"
                       type="text"
                       :placeholder="trans('settings.users.manage.last_name')"
                       id="last_name"
                       required
                       :class="{'is-invalid':form.errors.last_name}"/>
            <InputLabel for="last_name" :value="trans('settings.users.manage.last_name')" required/>
            <InputError :message="form.errors.last_name"/>
        </FormField>
        <FormField class="form-floating">
            <TextInput v-model.trim="form.email"
                       type="email"
                       :placeholder="trans('settings.users.manage.email')"
                       id="email"
                       required
                       :class="{'is-invalid':form.errors.email}"/>
            <InputLabel for="email" :value="trans('settings.users.manage.email')" required/>
            <InputError :message="form.errors.email"/>
        </FormField>
    </Card>
</template>

<script setup>

import Card from "@/Components/ui/Card.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import {trans} from "@/Helpers/translations.js";
const props = defineProps({
    form:{
        type:Object,
        required:true
    }
})
</script>

<style scoped>

</style>
