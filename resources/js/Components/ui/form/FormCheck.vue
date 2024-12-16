<template>
    <div class="form-check">
        <input class="form-check-input"
               :class="{'is-invalid':isInvalid}"
               type="checkbox"
               :value="value"
               :id="id"
               v-model="proxyChecked">
        <label class="form-check-label " :for="id">
            <template v-if="label">{{ label }}</template>
            <slot v-else />
        </label>
        <InputError :message="isInvalid"/>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import InputError from "@/Components/ui/form/InputError.vue";

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
    isInvalid: {
        type:String,
        default: "",
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
