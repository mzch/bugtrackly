<template>
  <div class="project-photo">
    <span v-if="dataPhotoPreview"
          :style="'background-image: url(\'' + dataPhotoPreview + '\');'"></span>
    <template v-else-if="projects?.project_photo_url && !form.delete_old_photo">
      <img :src="projects.project_photo_url" />
    </template>
    <template v-else>
      <span class="default"><ComputerDesktopIcon class="size-5"/></span>
    </template>
  </div>
  <InputError :message="form.errors.photo" class="text-center mb-2" :class="{'d-block':form.errors.photo}"/>
  <input
      id="photo"
      ref="photoInput"
      type="file"
      class="d-none"
      accept="image/png, image/jpeg"
      @change="updatePhotoPreview"/>
  <div class="d-flex justify-content-center mt-2">
    <button type="button"
            class="btn btn-outline-primary btn-with-icon btn-sm"
            @click.prevent="selectNewPhotoHandler">
      <CameraIcon class="size-1 me-1"/>
        {{ trans('settings.users.avatar.import_photo') }}
    </button>
    <button type="button"
            class="btn btn-outline-danger btn-with-icon btn-sm ms-2"
            v-if="viewDeleteButton"
            @click.prevent="removePreviewPhotoHandler">
      <TrashIcon class="size-1 me-1"/>
        {{ trans('general.delete_action') }}
    </button>
  </div>
</template>

<script setup>
import {computed, ref} from "vue";
import {CameraIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import {usePage} from "@inertiajs/vue3";
import {ComputerDesktopIcon} from "@heroicons/vue/24/outline/index.js";
import InputError from "@/Components/ui/form/InputError.vue";
import {trans} from "../../../../../Helpers/translations.js";

const props = defineProps({
  form: {
    type: Object,
    required: true
  }
})
const projects = computed(()=>usePage().props.project);
const photoInput = ref(null);
const dataPhotoPreview = ref(null);
const hasLocalPhoto = computed(() => projects.value?.project_photo_url || false )
const viewPreviewPhoto = computed(()=>dataPhotoPreview.value !== null)
/**
 * On cache le bouton de suppression d'avatar que s'il y a une preview ou que si
 * user.profile_photo_url commence par le domaine de l'app
 * @type {ComputedRef<boolean>}
 */
const viewDeleteButton = computed(() => viewPreviewPhoto.value || hasLocalPhoto.value);

/**
 * Handler sur le clic du bouton d'importation d'avatar'
 * @returns {*}
 */
const selectNewPhotoHandler = () => photoInput.value.click();

/**
 * Mise à jour de la prévisualisation de la photo
 */
const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0];
  if (!photo) return;

  props.form.clearErrors("photo")
  const reader = new FileReader();
  reader.onload = (e) => {
    dataPhotoPreview.value = e.target.result;
  };

  if (photoInput.value) {
    props.form.photo = photoInput.value.files[0];
  }
  reader.readAsDataURL(photo);
}

/**
 * Handler sur le clic du bouton de suppression d'avatar
 * @returns {*}
 */
const removePreviewPhotoHandler = () => {
  // un préview d'upload est affiché
  if(props.form.photo){
    props.form.reset("photo")
    props.form.clearErrors("photo")
    dataPhotoPreview.value = null;
    clearPhotoFileInput();
  }else{
    props.form.delete_old_photo = true;
    dataPhotoPreview.value = null;
  }
}
const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null;
  }
}
</script>
