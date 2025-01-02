<template>
    <AuthenticatedLayout :page-title="`${project.name} - Nouveau bug`">
        <template #header>{{ project.name }} - <span class="text-secondary">Rapporter un nouveau bug</span></template>
        <template #headerActions>
            <Link :href="route('projects.show', project.slug)" class="btn btn-primary btn-with-icon btn-sm">
                <ArrowLeftIcon class="size-1 me-1"/>
                Retour
            </Link>
        </template>
        <FormCard card-title="Nouveau bug" :submit-handler-fn-callback="createBugHandler">
            <FormField class="form-floating">
                <TextInput
                    id="bug_title"
                    class="form-control-lg"
                    placeholder="Titre du bug"
                    v-model.trim="form.title"
                    type="text"
                    autofocus
                    required
                    maxlength="255"
                    :class="{'is-invalid' :form.errors.title}"
                />
                <InputLabel for="bug_title" value="Titre du bug"/>
                <InputError :message="form.errors.title"/>
            </FormField>
            <FormField class="form-floating">
                <TextArea
                    id="bug_desc"
                    placeholder="Description"
                    v-model.trim="form.content"
                    required
                    style="height: 200px"
                    :class="{'is-invalid' :form.errors.content}"/>
                <InputLabel for="bug_desc" value="Description"/>
                <InputError :message="form.errors.content"/>
            </FormField>

            <FormField class="form-floating">
                <FormSelect id="bug_priority" :options="priorities_options" v-model.number="form.priority"/>
                <InputLabel for="bug_priority" value="Priorité"/>
                <InputError :message="form.errors.priority"/>
            </FormField>
            <template #cardFooter>
                <div class="d-flex align-items-center justify-content-end">
                    <PrimaryButton type="submit" :disabled="form.processing || !form.isDirty">Enregistrer</PrimaryButton>
                </div>
            </template>
        </FormCard>
    </AuthenticatedLayout>
</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {ArrowLeftIcon} from "@heroicons/vue/24/outline/index.js";
import FormCard from "@/Components/ui/FormCard.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import {computed} from "vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import TextArea from "@/Components/ui/form/TextArea.vue";

const props = defineProps({
    project:{
        type:Object,
        required:true,
    },
    bug_priorities:{
        type:Array,
        required:true,
    }
})
const form = useForm({
    title:"",
    content:"",
    priority:3,
})

const priorities_options = computed(() => {
    const priorities = props.bug_priorities || [];
    return priorities.map(p => ({id: p.id, label: p.label}));
});

const createBugHandler = () => {
    console.log("createBugHandler")
    form.post(route('projects.bug.store', props.project.slug), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
        },
    });
}
</script>
