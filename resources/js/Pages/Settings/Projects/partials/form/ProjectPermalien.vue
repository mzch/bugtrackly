<template>
    <div class="d-flex align-items-center text-sm mt-2">
        <strong class="me-1">Permalien :</strong>
        <template v-if="!editing_slug">
            <Link :href="route('projects.show', original_slug)">
                <span>{{ route('projects.index') }}/</span>
                <span>{{ form.slug }}</span>
            </Link>
            <button type="button" class="btn btn-outline-secondary py-1 btn-sm ms-1" @click="showEditSlug">
                Modifier
            </button>
        </template>
        <template v-else>
            <span>{{ route('projects.index') }}/</span>
            <TextInput v-model="slug"
                       @keydown.prevent.enter="valideSlugHandler"
                       ref="slugField"
                       class="form-control-sm w-auto px-1 ms-1"/>
            <button type="button" class="btn btn-sm btn-secondary ms-1" @click="valideSlugHandler">Ok</button>
            <button type="button" class="btn btn-sm btn-link ms-1" @click="cancelEditingSlug">Annuler</button>
        </template>
    </div>
</template>

<script setup>
import {nextTick, ref} from "vue";
import {Link, useForm, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/ui/form/TextInput.vue";
const editing_slug = ref(false);

const props = defineProps({
    form: {
        type: Object,
        required: true
    }
})
const slug = ref(props.form.slug);
const slugField = ref(null);
const original_slug = ref(props.form.slug);

const showEditSlug = () => {
    editing_slug.value = true;
    nextTick(() => slugField.value.focus());
};
const valideSlugHandler = () => {
    axios.post(route('settings.projects.validate_slug',usePage().props.project.id ), {
        slug: slug.value
    })
        .then(response => {
            const safeSlug = response.data.safeSlug;
            props.form.slug = safeSlug;
            slug.value= safeSlug;
        })
        .catch((error) => slug.value= props.form.slug)
        .finally(() => {
            editing_slug.value = false;
        })
}

const cancelEditingSlug = () => {
    slug.value = props.form.slug;
    editing_slug.value = false
};
</script>
