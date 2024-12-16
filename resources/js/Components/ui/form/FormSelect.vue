<template>
    <select class="form-select" v-model="model" ref="select">
        <option v-if="displaySelectLabelOption" :disabled="disableFirstOption" :value="null">{{ selectLabel }}</option>
        <option v-for="option in options"
                :key="option.id"
                :value="option.id"
        >
            {{ option.label }}
        </option>
    </select>
</template>

<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
    selectLabel:{
        type:String,
        default:"Sélectionnez"
    },
    displaySelectLabelOption:{
      type:Boolean,
      default:true,
    },
    disableFirstOption: {
        type: Boolean,
        default: true, // Par défaut, la première option sera désactivée
    },
    options:{
        type:Array,
        required:true,
    }
})
const model = defineModel({
    type: [String,Number, null],
    required: true,
});
const select = ref(null);

onMounted(() => {
    if (select.value.hasAttribute('autofocus')) {
        select.value.focus();
    }
});
defineExpose({ focus: () => select.value.focus() });
</script>
