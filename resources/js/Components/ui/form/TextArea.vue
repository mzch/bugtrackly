<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: [String, null],
    required: true,
});

const textarea = ref(null);

onMounted(() => {
    if (textarea.value.hasAttribute('autofocus')) {
        textarea.value.focus();
    }
});

defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <textarea
        class="form-control"
        v-model="model"
        ref="textarea"
    />
</template>
