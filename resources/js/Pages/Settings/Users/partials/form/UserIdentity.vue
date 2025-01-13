<template>
    <Card card-title="Identité de l'utilisateur" class="mb-5">
        <FormField class="form-floating">
            <TextInput v-model.trim="form.first_name"
                       type="text"
                       placeholder="Prénom"
                       id="first_name"
                       :class="{'is-invalid':form.errors.first_name}"
                       autofocus
                       required
            />
            <InputLabel for="first_name" value="Prénom" required/>
            <InputError :message="form.errors.first_name"/>
        </FormField>
        <FormField class="form-floating">
            <TextInput v-model.trim="form.last_name"
                       type="text"
                       placeholder="Nom"
                       id="last_name"
                       required
                       :class="{'is-invalid':form.errors.last_name}"/>
            <InputLabel for="last_name" value="Nom" required/>
            <InputError :message="form.errors.last_name"/>
        </FormField>
        <FormField class="form-floating">
            <TextInput v-model.trim="form.email"
                       type="email"
                       placeholder="Adresse email"
                       id="email"
                       required
                       :class="{'is-invalid':form.errors.email}"/>
            <InputLabel for="email" value="Email" required/>
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
const props = defineProps({
    form:{
        type:Object,
        required:true
    }
})
</script>

<style scoped>

</style>
