<template>

    <div class="d-flex align-items-center text-sm fixed-height">
        <template v-if="is_visible">
            <strong class="me-1">{{ trans('settings.projects.form.permalink') }}</strong>
            <span>{{ route('projects.index') }}/</span>
            <template v-if="!editing_slug">

                <strong>{{ slug }}</strong>
                <button type="button" tabindex="-1" class="btn btn-outline-secondary py-1 btn-sm ms-1" @click="showEditSlug">
                    {{ trans('general.modify_action') }}
                </button>
            </template>
            <template v-else>
                <TextInput v-model="slug"
                           @keydown.prevent.enter="valideSlugHandler"
                           @change="changeSlugHandler"
                           maxlength="255"
                           ref="slugField"
                           class="form-control-sm w-auto px-1 ms-1"/>
                <button type="button" class="btn btn-sm btn-secondary ms-1" @click="valideSlugHandler">Ok</button>
                <button type="button" class="btn btn-sm btn-link ms-1" @click="cancelEditingSlug">Annuler</button>
            </template>
        </template>
    </div>

</template>

<script setup>
import {computed, nextTick, ref} from "vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import {trans} from "@/Helpers/translations.js";

const props = defineProps({
    form: {
        type: Object,
        required: true
    }
})
const editing_slug = ref(false);
const slug = ref(props.form.slug);
const slugField = ref(null);
const is_visible = ref(false);
const showEditSlug = () => {
    editing_slug.value = true;
    nextTick(() => slugField.value.focus());
};
const cancelEditingSlug = () => {
    slug.value = props.form.slug;
    editing_slug.value = false
};

const changeSlugHandler = () => {
    if((!props.form.slug || props.form.slug === '') && props.form.name && props.form.name !== ''){
        createSlugFromProjectName();
    }
}

const createSlugFromProjectName = () => {
    axios.post(route('settings.projects.create_slug'), {
        name: props.form.name
    })
        .then(response => {
            const safeSlug = response.data.safeSlug;
            props.form.slug = safeSlug;
            slug.value= safeSlug;
        })
}
const valideSlugHandler = () => {
    const noLocalSlug = slug.value === null || slug.value.toString().trim() === "";
    if(noLocalSlug){
        props.form.slug = null;
        changeSlugHandler();
    }else{
        axios.post(route('settings.projects.create_slug'), {
            name: slug.value
        })
            .then(response => {
                const safeSlug = response.data.safeSlug;
                props.form.slug = safeSlug;
                slug.value= safeSlug;
            })
    }

    editing_slug.value = false;

    if(noLocalSlug && (props.form.name === null || props.form.name.toString().trim() === "")){
        is_visible.value = false;
    }

}
const generate_first_permalink = () => {
    if(props.form.name && !props.form.slug){
        createSlugFromProjectName();
        is_visible.value = true;
    }
}
defineExpose({generate_first_permalink})
</script>

<style scoped>
.fixed-height {
    height: 28px;
}
</style>
