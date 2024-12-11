<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div class="invalid-feedback" v-show="message">
        {{ message }}
    </div>
</template>
