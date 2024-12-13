<template>
    <div class="user-avatar" :class="{'user-avatar-initials': !user.profile_photo_url}">
        <img v-if="user.profile_photo_url" :src="user.profile_photo_url" class="img-fluid"/>
        <span v-else
            class="initiales">
            {{ user.initiales }}
        </span>
    </div>
</template>

<script setup>
    const props = defineProps({
        user:{
            type:Object,
            required:true
        }
    })
</script>
