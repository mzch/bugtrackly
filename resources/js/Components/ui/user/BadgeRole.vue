<template>

    <span v-if="role" class="badge rounded-pill" :class="classRoleBadge">{{ role.name }}</span>
    <small v-else>-</small>

</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    role: {
        type: [Object, null],
        required: true
    }
})

const classRoleBadge = computed(() => {
    switch (props.role.internal_name) {
        case 'admin':
            return 'text-bg-purple';
        case 'reporter':
            return 'text-bg-gray';
    }
})
</script>
