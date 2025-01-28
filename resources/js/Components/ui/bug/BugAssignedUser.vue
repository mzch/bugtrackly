<template>
    <p class="fw-semibold mb-2">
        {{ trans('bug.assignedToTitle') }}
    </p>
    <div class="d-flex align-items-center">
        <Avatar :user="user" class="bordered me-2 "/>
        {{user.full_name}}
    </div>
</template>

<script setup>
    import Avatar from "@/Components/ui/user/avatar.vue";
    import {trans} from "../../../Helpers/translations.js";
    const props = defineProps({
        user:{
            type:Object,
            required:true
        }
    })
</script>

<style scoped>

</style>
