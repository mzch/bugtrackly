<template>
    <FormCard :card-title="trans('settings.users.avatar.title')" :submit-handler-fn-callback="submithandler">
        <p class="form-text text-center">{{ trans('profile.avatar.info') }}</p>
        <p class="text-center">
            <AvatarGravatar class="size-5" :user="fakeUser" :preview-upload-image="dataPhotoPreview"/>
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
                <CameraIcon class="size-1 me-1"/>
                {{trans('settings.users.avatar.import_photo')}}
            </PrimaryButton>
            <DangerButton outlined class="btn-with-icon btn-sm ms-2"
                          v-if="viewDeleteButton"
                          @click.prevent="removePreviewPhotoHandler">
                <TrashIcon class="size-1 me-1"/>
                {{trans('general.delete_action')}}
            </DangerButton>
        </div>
        <template #cardFooter>
            <div class="d-flex align-items-center justify-content-between">
                <PrimaryButton type="submit" :disabled="form.processing || !form.isDirty">{{trans('general.save_action')}}</PrimaryButton>
                <Transition
                    enter-active-class="transition-opacity ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition-opacity ease-in-out"
                    leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="mb-0 text-success fw-bold">
                        Enregisté !
                    </p>
                </Transition>
            </div>
        </template>
    </FormCard>
</template>

<script setup>
import AvatarGravatar from "@/Components/ui/user/AvatarGravatar.vue";
import {computed, ref} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import InputError from "@/Components/ui/form/InputError.vue";
import {CameraIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import DangerButton from "@/Components/ui/form/DangerButton.vue";
import FormCard from "@/Components/ui/FormCard.vue";
import {generateInitials} from "@/Helpers/users.js";
import {trans} from "@/Helpers/translations.js";

const user = computed(() => usePage().props.auth.user);
const photoInput = ref(null);
const dataPhotoPreview = ref(null);
const form = useForm({
    photo: null,
    delete_old_photo:false,
})

const fakeUser = computed(() => {
    const firstname = user.value.first_name ? user.value.first_name.toString().trim() : '',
        lastname = user.value.last_name ? user.value.last_name.toString().trim() : '',
        email = user.value.email ? user.value.email.toString().trim() : '';
    return {
        initiales: generateInitials(`${firstname} ${lastname}`),
        email: email,
        profile_photo_url: !form.delete_old_photo ? user.value.profile_photo_url: null
    }
});

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
    form.post(route('profile.update_avatar'), {
        forceFormData:true,
        onSuccess: () => {
            form.reset()
        },
        onError:() => {

        }
    })
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
        dataPhotoPreview.value = null;
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
