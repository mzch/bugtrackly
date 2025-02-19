<template>
    <div class="upload-zone text-center p-3">
        <h5 class="fs-6">{{ trans('ticket.form.add_files_label') }}</h5>
        <div class="drop-zone d-flex flex-column align-items-center p-4 mt-3 text-secondary"
             @dragover.prevent="handleDragOver"
             @dragenter.prevent="handleDragEnter"
             @dragleave.prevent="handleDragLeave"
             @drop.prevent="handleDrop">
            <input type="file" multiple ref="fileInput" class="visually-hidden" id="upload_file" @change="handleFileChange"/>
            <ArrowUpTrayIcon class="size-2 mb-2"/>
            <InputLabel for="upload_file" class="text-secondary text-sm">
                {{trans('ticket.form.add_files_placeholder')}}
            </InputLabel>
            <SecondaryButton @click="triggerFileInput" outlined class="btn-sm mt-2">
                {{ model.length ? trans('ticket.form.add_files_btn_choose_other_files') : trans('ticket.form.add_files_btn_choose_files') }}
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
import {computed, onBeforeUnmount, onMounted, ref} from "vue";
import {TrashIcon} from "@heroicons/vue/24/outline/index.js";
import {useStore} from "vuex";
import {trans} from "../../../Helpers/translations.js";

// Définir la prop pour les fichiers
const model = defineModel({ required: true,default: [] })
const store = useStore();
const editing_bug_part = computed(()=> store.getters['bug/editingBug'])
const props = defineProps({
    authorizePasteWhenEditing:{
        type:[String,Boolean],
        required:true
    }
})

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

/**
 * Gérer l'événement dragover pour permettre le dépôt des fichiers
 */
const handleDragOver = (event) => {
    // Permettre le dépôt
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
};

const resetFileInput = () => {
    if (fileInput.value) {
        fileInput.value.value = null; // Réinitialiser l'input
    }
};


/**
 * Gérer l'événement dragenter pour donner un effet visuel pendant le survol
 */
const handleDragEnter = (event) => event.target.classList.add('drag-over');

/**
 * Gérer l'événement dragleave pour supprimer l'effet visuel
 */
const handleDragLeave = (event) => event.target.classList.remove('drag-over');

/**
 * Gérer l'événement drop pour traiter les fichiers déposés
 */
const handleDrop = (event) => {
    const droppedFiles = Array.from(event.dataTransfer.files); // Extraire les fichiers déposés
    model.value = [...model.value, ...droppedFiles]; // Ajouter les fichiers déposés
    event.target.classList.remove('drag-over');
};

/**
 * Gérer l'événement paste pour gérer les fichiers copiés/collés
 */
const handlePaste = (event) => {
    if(editing_bug_part.value!== props.authorizePasteWhenEditing){
        return;
    }
    const clipboardItems = event.clipboardData.items; // Récupérer les éléments collés
    const files = [];

    // Parcourir les éléments du presse-papier
    for (let i = 0; i < clipboardItems.length; i++) {
        const item = clipboardItems[i];
        if (item.kind === 'file') {
            const file = item.getAsFile();
            if (file) {
                files.push(file); // Ajouter le fichier à la liste
            }
        }
    }

    if (files.length > 0) {
        model.value = [...model.value, ...files]; // Ajouter les fichiers collés à la liste
    }
};

defineExpose({resetFileInput})
// Ajouter l'écouteur d'événements au montage
onMounted(() => {
    window.addEventListener("paste", handlePaste);
});

// Retirer l'écouteur au démontage
onBeforeUnmount(() => {
    window.removeEventListener("paste", handlePaste);
});
</script>
