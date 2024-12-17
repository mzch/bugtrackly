<template>
    <div :class="classes">
        <slot/>
    </div>
</template>
<script setup>
    import {computed} from "vue";

    const props = defineProps({
        noMarginBottom:{
            type:Boolean,
            default:false,
            required:false,
        }
    })

    const classes = computed(() => props.noMarginBottom ? 'mb-0' : 'mb-3')
</script>
