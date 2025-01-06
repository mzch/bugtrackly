<template>
    <InputLabel :for="id">{{ label }}</InputLabel>
    <v-select v-model.number="model"
              :inputId="id"
              class="user-avatar-list"
              label="full_name"
              :reduce="user => user.id"
              placeholder="Sélectionnez un utilisateur"
              :options="users">
        <template #open-indicator="{ attributes }">
                            <span v-bind="attributes">
                                <svg xmlns='http://www.w3.org/2000/svg' style="width: 16px; height: 12px" viewBox='0 0 16 16'><path fill='none' stroke='#343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/></svg>
                            </span>
        </template>
        <template #option="option">
            <div class="d-flex align-items-center">
                <Avatar :user="option" class="me-1 bordered"/>
                {{ option.full_name }}
            </div>
        </template>
        <template #selected-option="option">
            <div class="d-flex align-items-center">
                <Avatar :user="option" class="me-1 bordered"/>
                {{ option.full_name }}
            </div>
        </template>
    </v-select>
</template>

<script setup>

import InputLabel from "@/Components/ui/form/InputLabel.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
const model = defineModel({
    type: [String,Number, null],
    required: true,
});
const props = defineProps({
    label:{
        type:String,
        default:'Liste des utilisteurs'
    },
    users:{
        type:Array,
        required:true
    },
    id:{
        type:String,
        required:true
    }
})
</script>

<style scoped>

</style>
