<template>
    <div class="user-avatar" :class="{'user-avatar-initials': !hasGravatar || !previewUploadImage}">
         <span v-if="previewUploadImage"
               class="d-block rounded-circle w-100 h-100"
               :style="'background-image: url(\'' + previewUploadImage + '\');'"></span>
        <img v-else-if="hasGravatar" :src="gravatar_url" class="img-fluid"/>

        <span v-else
              class="initiales">
            {{ user.initiales }}
        </span>
    </div>
</template>

<script setup>
import {computed, ref, watch} from "vue";
import {getGravatarUrl, gravatarExists, validateEmail} from "@/Helpers/users.js";
import {throttle} from "lodash";

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
    const email = props.user.email ? props.user.email.toString().trim() : '';
    const url = getGravatarUrl(email);
    console.log(hasGravatar.value, url);
    return url;
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
