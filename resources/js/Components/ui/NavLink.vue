<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import * as Heroicons from '@heroicons/vue/24/outline';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    icon:{
        type: String,
        required: false,
    },
});

const iconComponent = computed(() => (props.icon ? Heroicons[props.icon] : null));
</script>

<template>
    <Link :href="href" >
        <component
            v-if="iconComponent"
            :is="iconComponent"
            class="icon-menu"
        />
        <slot />
    </Link>
</template>
