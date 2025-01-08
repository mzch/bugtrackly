<template>
    <div class="user-avatar" :class="{'user-avatar-initials': !user?.profile_photo_url, 'bordered':bordered}">
        <img v-if="user?.profile_photo_url" :src="user.profile_photo_url" />
        <span v-else
            class="initiales">
            {{ user?.initiales || "X" }}
        </span>
    </div>
</template>

<script setup>
    const props = defineProps({
        user:{
            type:[Object, null],
            required:true
        },
        bordered:{
            type:Boolean,
            required:false,
            default:false,
        }
    })
</script>
