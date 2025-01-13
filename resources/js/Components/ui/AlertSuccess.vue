<template>
    <div class="alert alert-success" :class="classes">
        <slot/>
        <button v-if="props.dismissible" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</template>

<script setup>
import {computed} from "vue";
import Alert from 'bootstrap/js/dist/alert';
const props = defineProps({
    dismissible: {
        type: Boolean,
        required: false,
        default: false,

    }
})
const classes = computed(() => props.dismissible ? 'alert-dismissible fade show' : '')
</script>
