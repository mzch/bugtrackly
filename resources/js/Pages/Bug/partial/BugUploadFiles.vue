<template>
    <div class="upload-zone text-center p-3">
        <h5 class="fs-6">Téléverser des fichiers</h5>
        <div class="drop-zone d-flex flex-column align-items-center p-5 mt-3 text-secondary">
            <input type="file" multiple ref="fileInput" class="visually-hidden" id="upload_file" @change="handleFileChange"/>
            <ArrowUpTrayIcon class="size-2 mb-2"/>
            <InputLabel for="upload_file" class="text-secondary text-sm">
                Joignez les fichiers en les glissant-déposant, en les sélectionnant ou en les collant.
            </InputLabel>
            <SecondaryButton @click="triggerFileInput" outlined class="btn-sm mt-2">
                {{ model.length ? "Ajouter d'autres fichiers" : "Choisir des fichiers" }}
            </SecondaryButton>
        </div>
        <div>
        <ul class="list-group  mb-0 text-start text-sm mt-3" v-if="model.length">
            <li class="list-group-item d-flex justify-content-between align-items-center"
                v-for="(f, index) in model" :key="index">
                {{ f.name }}
                <button type="button" class="btn btn-sm btn-link text-danger btn-with-icon" @click="removeFile(index)">
                    <TrashIcon class="size-1"/></button>
            </li>
        </ul>
        </div>
    </div>
</template>

<script setup>
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import {ArrowUpTrayIcon} from "@heroicons/vue/24/outline/index.js";
import SecondaryButton from "@/Components/ui/form/SecondaryButton.vue";
import {ref} from "vue";
import {TrashIcon} from "@heroicons/vue/24/outline/index.js";

const emit = defineEmits(["update:modelValue"])
// Définir la prop pour les fichiers
const model = defineModel({ required: true,default: [] })

// Réréfence au champ input file
const fileInput = ref(null);

/**
 * Fonction déclenchée au clic sur le bouton "parcourir..."
 */
const triggerFileInput = () => fileInput.value.click();

/**
 * Fonction déclenchée lors de la sélection de documents
 */
const handleFileChange = (event) => {
    const newFiles = Array.from(event.target.files); // Convertir FileList en tableau
    model.value = [...model.value, ...newFiles]; // Ajouter les nouveaux fichiers
};

/**
 * Fonction déclenchée pour supprimer un fichier de la liste des fichiers à téléverser
 * @param index
 */
const removeFile = (index) => {
    const updatedFiles = [...model.value];
    updatedFiles.splice(index, 1); // Supprimer le fichier à l'index
    model.value = updatedFiles; // Mettre à jour la liste
};

</script>
