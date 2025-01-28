<template>
    <p class="mb-0">
        <template v-if="no_modified">{{ trans('general.publish') }}</template>
        <template v-else>{{ trans('general.lastModification') }}</template>
        <br>
        {{trans_params('general.onDate', formatDate(project.updated_at, trans('general.date-with-hour')))}}
    </p>
</template>

<script setup>
import {computed} from "vue";
import {formatDate} from "../../../Helpers/date.js";
import {trans, trans_params} from "../../../Helpers/translations.js";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    }
})

const no_modified = computed(() => props.project.created_at === props.project.updated_at)
</script>
