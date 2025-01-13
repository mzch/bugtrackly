<template>
    <button :type="type" class="btn" :class="classes">
        <slot />
    </button>
</template>
<script setup>
import {computed} from "vue";

const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    outlined:{
        type: Boolean,
        default: false,
    }
});
const classes = computed(() => props.outlined ? "btn-outline-danger" : "btn-danger")
</script>
