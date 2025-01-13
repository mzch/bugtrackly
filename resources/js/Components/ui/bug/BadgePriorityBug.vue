<template>
    <span :class="classes">
        {{ priorityLabel }}
    </span>
</template>

<script setup>
import {getPriorityObject} from "../../../Helpers/bug.js";
import {computed} from "vue";

const props = defineProps({
    bug: {
        type: Object,
        required: true,
    },
    extendedLabel :{
        type: Boolean,
        required: false,
        default: false,
    }
})

const priority = computed(() => getPriorityObject(props.bug.priority))
const priorityLabel = computed(() => props.extendedLabel ? priority.value.extended_label : priority.value.label)
const classes = computed(() => `badge text-bg-priority-${priority.value.weight}`)
</script>

<style scoped>

</style>
