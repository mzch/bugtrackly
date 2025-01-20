<template>
    <div class="avatar-container" :class="sizeAvatar">
        <div
            v-for="(user, index) in visibleUsers"
            :key="user.id"
            class="avatar"
            :style="{ zIndex: visibleUsers.length - index, left: `${index * overlap}px` }"
        >
            <Avatar :class="sizeAvatar"
                    :bordered="true"
                    :user="user"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    :data-bs-title="user.full_name"/>
        </div>
        <div v-if="hiddenCount > 0" class="avatar more" :style="{ left: `${visibleUsers.length * overlap}px` }">
            ...
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, onUnmounted, ref} from "vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {disposeToolTips, enableToolTips} from "@/Helpers/bs_tooltips.js";
const tooltipList = ref([]);
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
    sizeAvatar:{
        type:String,
        required:false,
        default:"size-3"
    }
})

const visibleUsers = computed(() => props.items.slice(0, props.maxVisible))
const hiddenCount = computed(() => props.items.length - props.maxVisible)

onMounted(() => enableToolTips(tooltipList))
onUnmounted(() => disposeToolTips(tooltipList))
</script>
<style type="scss" scoped>
.avatar-container {
    display: flex;
    position: relative;
    width: auto !important;
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
