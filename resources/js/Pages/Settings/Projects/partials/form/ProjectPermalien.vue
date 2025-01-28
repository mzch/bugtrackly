<template>
    <div class="d-flex align-items-center text-sm">
            <strong class="me-1">{{ trans('settings.projects.form.permalink') }}</strong>
            <template v-if="!editing_slug">
                <a :href="route('projects.show', original_slug)" target="_blank">
                    <span>{{ route('projects.index') }}/</span>
                    <strong>{{ form.slug }}</strong>
                </a>
                <button type="button" class="btn btn-outline-secondary py-1 btn-sm ms-1" @click="showEditSlug">
                    {{ trans('general.modify_action') }}
                </button>
            </template>
            <template v-else>
                <span>{{ route('projects.index') }}/</span>
                <TextInput v-model="slug"
                           @keydown.prevent.enter="valideSlugHandler"
                           maxlength="255"
                           ref="slugField"
                           class="form-control-sm w-auto px-1 ms-1"/>
                <button type="button" class="btn btn-sm btn-secondary ms-1" @click="valideSlugHandler">Ok</button>
                <button type="button" class="btn btn-sm btn-link ms-1" @click="cancelEditingSlug">Annuler</button>
            </template>



    </div>

</template>

<script setup>
import {computed, nextTick, ref} from "vue";
import {Link, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/ui/form/TextInput.vue";
import {trans} from "../../../../../Helpers/translations.js";
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
        slug: slug.value,
        name:props.form.name
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
