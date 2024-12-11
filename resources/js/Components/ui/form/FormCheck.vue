<template>
    <div class="form-check">
        <input class="form-check-input"
               type="checkbox"
               :value="value"
               :id="id"
               v-model="proxyChecked">
        <label class="form-check-label " :for="id">
            <template v-if="label">{{ label }}</template>
            <slot v-else />
        </label>
    </div>
</template>

<script setup>
import {computed} from "vue";

const emit = defineEmits(['update:checked']);

const props = defineProps({
    id:{
        type:String,
        required:true,
    },
    label: {
        type: String,
        required:false
    },
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});

</script>

<style scoped>

</style>
