<template>
    <button :type="type" class="btn btn-danger">
        <slot />
    </button>
</template>
<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>
