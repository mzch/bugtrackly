<template>
    <template v-if="!editMode">
        <TagIcon class="size-1 handle-drag"/>
        {{ model.name }}
        <div class="row-actions d-inline-block">
            <button @click="clickEditHandler"
                    type="button"
                    class="btn btn-link btn-sm p-0">
                Modifier
            </button>
            <span class="mx-1 text-gray">|</span>
            <button @click="emits('clickDeleteCategory', index)"
                    type="button"
                    class="btn btn-link text-danger btn-sm p-0">
                Supprimer
            </button>
        </div>
    </template>
    <template v-else>
        <div class="input-group">
            <span class="input-group-text">
                <TagIcon class="size-1"/>
            </span>
            <TextInput v-model="model.name"
                       ref="inputRef"
                       @keydown.enter.prevent="clickOkHandler"
                       class="form-control-sm"/>
            <PrimaryButton type="button" outlined @click="clickOkHandler">Enregistrer</PrimaryButton>
        </div>
    </template>

</template>

<script setup>
import {nextTick, ref, watch} from "vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import {TagIcon} from "@heroicons/vue/24/outline/index.js";

const emits = defineEmits(['clickDeleteCategory', 'clickEdit', 'clickEndEditing'])
const model = defineModel({
    type: Object,
    required: true,
});
const props = defineProps({
    index: {
        type: Number,
        required: true
    },
    editingIndex: {
        type: [Number,null],
        required: true
    }
})
const inputRef = ref(null);
const editMode = ref(false);

const clickOkHandler = (event) => {
    if (!event.isComposing) {
        editMode.value = false;
        emits('clickEndEditing', props.index)
    }
}
const clickEditHandler = () => {
    editMode.value = true;
    emits('clickEdit', props.index)
    nextTick(() => inputRef.value.input.focus())
};

watch(() => props.editingIndex, (newValue) => {
    if(newValue !== null && newValue !== props.index){
        editMode.value = false;
    }
})
</script>
<style scoped>

</style>
