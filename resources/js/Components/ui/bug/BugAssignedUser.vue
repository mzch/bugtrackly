<template>
    <p class="fw-semibold mb-2">
        Assigné à :
    </p>
    <div class="d-flex align-items-center">
        <Avatar :user="user" class="bordered me-2 "/>
        {{user.full_name}}
    </div>
</template>

<script setup>
    import Avatar from "@/Components/ui/user/avatar.vue";
    const props = defineProps({
        user:{
            type:Object,
            required:true
        }
    })
</script>

<style scoped>

</style>
