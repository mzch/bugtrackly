<template>
    <p class="fw-semibold mb-2">
        {{ trans('ticket.followedByTitle') }}
    </p>
    <AvatarsList size-avatar="size-2" :items="followers" v-if="followers.length > 1"/>
    <div class="d-flex align-items-center" v-else>
        <Avatar :user="followers[0]" class="bordered me-2 "/>
        {{followers[0].full_name}}
    </div>
</template>

<script setup>


    import AvatarsList from "@/Components/ui/user/AvatarsList.vue";
    import Avatar from "@/Components/ui/user/avatar.vue";
    import {trans} from "../../../Helpers/translations.js";

    const props = defineProps({
        followers:{
            type:Array,
            required:true
        }
    })
</script>

<style scoped>

</style>
