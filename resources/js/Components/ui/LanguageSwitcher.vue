<template>
    <FormSelect :disabled="form_processing" class="form-select-sm w-auto d-inline-block" :options="locales_for_dropdown" v-model="locale"/>
</template>

<script setup>

import FormSelect from "@/Components/ui/form/FormSelect.vue";
import {computed, ref, watch} from "vue";
import {map} from "lodash";
import {router, usePage} from "@inertiajs/vue3";

const locales_for_dropdown = computed(() => map(usePage().props.available_locales, (label, id) => ({id, label})))
const locale = ref(usePage().props.current_locale)
const form_processing = ref(false);

watch(locale, (newLocale) => {
    form_processing.value = true;
    axios.post(route('set-locale'),{locale:newLocale})
        .then(()=>router.reload({only:['current_locale','translations', 'breadcrumb', 'bug_status','bug_priorities']}))
        .catch(error => console.error(error))
        .finally(() => form_processing.value = false)
})

</script>
