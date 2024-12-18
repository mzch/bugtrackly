<template>
    <FormCard card-title="Ma photo de profil" :submit-handler-fn-callback="submithandler">
        <p class="form-text text-center">Personnalisez votre image de profil</p>
        <pre>{{form}}</pre>
        <p class="text-center">
            <AvatarGravatar class="size-5" :user="user" :preview-upload-image="dataPhotoPreview"/>
        </p>
        <InputError :message="form.errors.photo" class="text-center mb-2" :class="{'d-block':form.errors.photo}"/>
        <input
            id="photo"
            ref="photoInput"
            type="file"
            class="d-none"
            accept="image/png, image/jpeg"
            @change="updatePhotoPreview"/>
        <div class="d-flex justify-content-center">
            <PrimaryButton outlined class="btn-with-icon btn-sm" @click.prevent="selectNewPhotoHandler">
                <CameraIcon class="size-1 me-1"/>Importer une photo
            </PrimaryButton>
            <DangerButton outlined class="btn-with-icon btn-sm ms-2"
                          v-if="viewDeleteButton"
                          @click.prevent="removePreviewPhotoHandler">
                <TrashIcon class="size-1 me-1"/>Supprimer
            </DangerButton>
        </div>
        <template #cardFooter>
            <PrimaryButton type="submit" :disabled="form.processing || !form.isDirty">Enregistrer</PrimaryButton>
        </template>
    </FormCard>
</template>

<script setup>

import Card from "@/Components/ui/Card.vue";
import AvatarGravatar from "@/Components/ui/user/AvatarGravatar.vue";
import {computed, ref} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import InputError from "@/Components/ui/form/InputError.vue";
import {CameraIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import DangerButton from "@/Components/ui/form/DangerButton.vue";
import FormCard from "@/Components/ui/FormCard.vue";

const user = computed(() => usePage().props.auth.user);
const photoInput = ref(null);
const dataPhotoPreview = ref(null);
const form = useForm({
    photo: null,
    delete_old_photo:false,
})

const appDomain = window.location.origin;

const viewPreviewPhoto = computed(()=>dataPhotoPreview.value !== null)
const hasLocalPhoto = computed(() => user.value.profile_photo_url && user.value.profile_photo_url.startsWith(appDomain))
/**
 * On cache le bouto de suppression d'avatar que s'il y a une preview ou que si
 * user.profile_photo_url commence par le domaine de l'app
 * @type {ComputedRef<boolean>}
 */
const viewDeleteButton = computed(() => (viewPreviewPhoto.value || hasLocalPhoto.value ));

const submithandler = () => {
    console.log("submit");
}
/**
 * Mise à jour de la prévisualisation de la photo
 */
const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];
    if (!photo) return;

    form.clearErrors("photo")
    const reader = new FileReader();
    reader.onload = (e) => {
        dataPhotoPreview.value = e.target.result;
    };

    if (photoInput.value) {
       form.photo = photoInput.value.files[0];
    }
    reader.readAsDataURL(photo);
}

/**
 * Handler sur le clic du bouton de suppression d'avatar
 * @returns {*}
 */
const removePreviewPhotoHandler = () => {
    // un préview d'upload est affiché
    if(form.photo){
        form.reset("photo")
        form.clearErrors("photo")
        dataPhotoPreview.value = null;
        clearPhotoFileInput();
    }else{
        form.delete_old_photo = true;
    }
}

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
}

/**
 * Handler sur le clic du bouton d'importation d'avatar'
 * @returns {*}
 */
const selectNewPhotoHandler = () => photoInput.value.click();
</script>
