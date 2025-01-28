<template>
    <Card :card-title="trans('profile.language.title')" class="mb-4">
        <FormField class="form-floating">
            <LanguageSwitcher id="profile_ls"/>
            <InputLabel for="first_name" :value="trans('profile.language.label')" />
        </FormField>
    </Card>
</template>

<script setup>
import {trans} from "@/Helpers/translations.js";
import Card from "@/Components/ui/Card.vue";
import LanguageSwitcher from "@/Components/ui/LanguageSwitcher.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
</script>
