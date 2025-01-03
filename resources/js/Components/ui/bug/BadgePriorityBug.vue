<template>
    <span :class="classes">
        <template v-if="addPriorityLabel">Priorité</template>
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
    addPriorityLabel :{
        type: Boolean,
        required: false,
        default: false,
    }
})

const priority = computed(() => getPriorityObject(props.bug.priority))
const priorityLabel = computed(() => props.addPriorityLabel ? priority.value.label.toLowerCase() : priority.value.label)
const classes = computed(() => `badge text-bg-priority-${priority.value.weight}`)
</script>

<style scoped>

</style>
