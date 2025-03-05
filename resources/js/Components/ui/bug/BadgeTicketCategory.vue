<template>
    <span class="badge text-bg-light">
        <TagIcon class="size-sm"/>
        {{bug.ticket_category?.name ?? trans('ticket_cat_none')}}
    </span>
</template>

<script setup>
import {TagIcon} from "@heroicons/vue/24/outline/index.js";
import {trans} from "../../../Helpers/translations.js";
const props = defineProps({
    bug: {
        type: Object,
        required: true,
    }
})
</script>
