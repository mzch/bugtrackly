<template>
    <p class="mb-0">
        <template v-if="no_modified">{{ trans('general.publish') }}</template>
        <template v-else>{{ trans('general.lastModification') }}</template>
        <br>
        {{trans_params('general.onDate', formatDate(bug.updated_at, trans('general.date-with-hour')))}}
    </p>
</template>

<script setup>
import {formatDate} from "@/Helpers/date.js";
import {computed} from "vue";
import {trans, trans_params} from "../../../Helpers/translations.js";

const props = defineProps({
    bug: {
        type: Object,
        required: true,
    }
})
const no_modified = computed(() => props.bug.created_at === props.bug.updated_at)
</script>

<style scoped>

</style>
