<template>
    <template v-if="!isLink">
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill d-inline-flex align-items-center">
            <Avatar :user="user" class="me-1"/>
            {{ user.full_name }}
        </button>
    </template>
    <template v-else>
        <Link class="btn btn-outline-secondary btn-sm rounded-pill d-inline-flex align-items-center">
            <Avatar :user="user" class="me-1"/>
            {{ user.full_name }}
        </Link>
    </template>
</template>

<script setup>
import Avatar from "@/Components/ui/user/avatar.vue";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    isLink: {
        type: Boolean,
        required: false,
        default: false
    }
})
</script>
