<template>
    <div class="avatar-container">
        <div
            v-for="(user, index) in visibleUsers"
            :key="user.id"
            class="avatar"
            :style="{ zIndex: visibleUsers.length - index, left: `${index * overlap}px` }"
        >
            <Avatar class="size-3" :bordered="true" :user="user"/>
        </div>
        <div v-if="hiddenCount > 0" class="avatar more" :style="{ left: `${visibleUsers.length * overlap}px` }">
            ...
        </div>
    </div>
</template>

<script setup>
import {computed} from "vue";
import Avatar from "@/Components/ui/user/avatar.vue";

const props = defineProps({
    items :{
        type:Array,
        required:true,
    },
    maxVisible: {
        type: Number,
        default: 4,
    },
    overlap: {
        type: Number,
        default: 20, // Décalage entre les avatars
    },
})

const visibleUsers = computed(() => props.items.slice(0, props.maxVisible))
const hiddenCount = computed(() => props.items.length - props.maxVisible)
</script>
<style type="scss" scoped>
.avatar-container {
    display: flex;
    position: relative;
    height: 50px;
}
.avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    position: absolute;
    //transition: transform 0.2s ease;
}
.avatar.more {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #ddd;
    font-size: 13px;
    font-weight: bold;
    color: #555;
}
</style>
