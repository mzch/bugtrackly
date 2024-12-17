<template>
    <Card card-title="Rôle">
        <FormField class="form-floating">
            <FormSelect id="user-role"
                        :display-select-label-option="false"
                        :options="roles_options"
                        v-model.number="form.role_id"/>
            <InputLabel for="user-role" value="Sélectionnez un rôle" required/>
            <InputError :message="form.errors.role_id"/>
        </FormField>
    </Card>
</template>

<script setup>

import Card from "@/Components/ui/Card.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const props = defineProps({
    form:{
        type:Object,
        required:true
    }
})

const roles_options = computed(() => {
    const roles= usePage().props.roles || [];
    return roles.map(r => ({id: r.id, label: r.name}))
});
</script>

<style scoped>

</style>
