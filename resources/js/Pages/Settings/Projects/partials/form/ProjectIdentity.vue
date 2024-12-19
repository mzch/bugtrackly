<template>
    <Card card-title="Caractéristiques du projet">
        <div class="row">
            <div class="col-lg-8">
                <FormField class="form-floating">
                    <TextInput v-model.trim="form.name"
                               type="text"
                               placeholder="Nom du projet"
                               id="name"
                               maxlength="255"
                               class="form-control-lg"
                               :class="{'is-invalid':form.errors.name}"
                               autofocus
                               required
                    />
                    <InputLabel for="name" value="Nom du projet" required/>
                    <InputError :message="form.errors.name"/>
                    <ProjectPermalien class="mt-2" :form="form"/>
                </FormField>

                <FormField class="form-floating" no-margin-bottom>
                    <TextInput v-model.trim="form.short_desc"
                               type="text"
                               maxlength="255"
                               placeholder="Courte description"
                               id="name"
                               :class="{'is-invalid':form.errors.short_desc}"
                               autofocus
                               required
                    />
                    <InputLabel for="name" value="Courte description" required/>
                    <InputError :message="form.errors.name"/>
                </FormField>
            </div>
            <div class="col-lg-4">

            </div>
        </div>

    </Card>
</template>

<script setup>

import Card from "@/Components/ui/Card.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import ProjectPermalien from "@/Pages/Settings/Projects/partials/form/ProjectPermalien.vue";

const props = defineProps({
    form: {
        type: Object,
        required: true
    }
})
</script>
