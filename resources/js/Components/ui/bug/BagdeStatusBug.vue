<template>
    <span :class="classes">
    {{status.label}}
    </span>
</template>

<script setup>
import {computed} from "vue";
import { getStatusObject} from "@/Helpers/bug.js";

const props = defineProps({
    bug: {
        type: Object,
        required: true,
    }
})

const status = computed(() => getStatusObject(props.bug.status))
const classes = computed(() => `badge text-bg-status-${status.value.slug}`)
</script>

<style scoped>

</style>
