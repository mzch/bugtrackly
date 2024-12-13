<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div class="invalid-feedback mt-2" v-show="message">
        {{ message }}
    </div>
</template>
