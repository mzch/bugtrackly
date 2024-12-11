<template>
    <Link  :href="href"
           :class="classes"
           class="flex items-center py-1 px-2 transition w-full text-[13px]">
        <slot/>
    </Link>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import {computed} from "vue";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(()=> {
    return props.active ? 'text-white' : 'text-sub-menu-color'
})
</script>
