<template>
    <div class="user-avatar" :class="{'user-avatar-initials': !hasGravatar || !previewUploadImage || !profile_photo_url }">
         <span v-if="previewUploadImage"
               class="d-block rounded-circle w-100 h-100"
               :style="'background-image: url(\'' + previewUploadImage + '\');'"></span>
        <template v-else-if="user.profile_photo_url">
            <img :src="user.profile_photo_url" />
        </template>
        <template v-else-if="USE_GRAVATAR_SERVICE && hasGravatar">
            <img :src="gravatar_url" />
        </template>
        <template v-else-if="USE_INITIALES">
            <span class="initiales">{{ user.initiales }}</span>
        </template>
        <template v-else>
            <img src="/images/avatar_default.png"/>
        </template>
    </div>
</template>

<script setup>
import {computed, ref, watch} from "vue";
import {getGravatarUrl, gravatarExists} from "@/Helpers/users.js";
import {throttle} from "lodash";

const USE_GRAVATAR_SERVICE = import.meta.env.BUGTRACKLY_USER_AVATAR_USE_GRAVATAR === 'true';
const USE_INITIALES = import.meta.env.BUGTRACKLY_USER_AVATAR_USE_INITIALES === 'true';

const props = defineProps({
    user:{
        type:Object,
        required:true
    },
    previewUploadImage:{
        type:String,
        required:false
    }
})

// États réactifs
const hasGravatar = ref(false);

const gravatar_url = computed(() => {
    if(USE_GRAVATAR_SERVICE === false){
        console.log("ici");
        return false
    }
    const email = props.user.email ? props.user.email.toString().trim() : '';
    return getGravatarUrl(email);
});

// Fonction pour vérifier si le Gravatar existe
const checkGravatar = async (url_gravatar) => hasGravatar.value = await gravatarExists(url_gravatar);

const throttledCheckGravatar = throttle(checkGravatar, 250);

watch(gravatar_url, (newUrl, oldUrl) => {
    if(newUrl){
        throttledCheckGravatar(newUrl);
    }else{
        hasGravatar.value = false;
    }
}, {immediate:true})
</script>
