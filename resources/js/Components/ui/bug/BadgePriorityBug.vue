<template>
    <span :class="classes">
    {{priority.label}}
        </span>
</template>

<script setup>
import {getPriorityObject} from "../../../Helpers/bug.js";
import {computed} from "vue";

const props = defineProps({
    bug: {
        type: Object,
        required: true,
    }
})

const priority = computed(() => getPriorityObject(props.bug.priority))
const classes = computed(() => `badge text-bg-priority-${priority.value.weight}`)
</script>

<style scoped>

</style>
