<template>
    <button type="button"
            class="btn btn-link">
        <component
            v-if="iconComponent"
            :is="iconComponent"
            class="icon-menu size-1"
        />
        <slot/>
        <component v-if="!opened" :is="chevronDownComponent" class="carret ms-auto"/>
        <component v-else :is="chevronUpComponent" class="carret ms-auto"/>
    </button>
</template>

<script setup>
import * as Heroicons from '@heroicons/vue/24/outline';
import {computed} from "vue";

const props = defineProps({
    icon:{
        type: String,
        required: false,
    },
    opened:{
        type: Boolean,
        required: true,
        default: false,
    },
});
const iconComponent = computed(() => (props.icon ? Heroicons[props.icon] : null));
const chevronDownComponent = Heroicons['ChevronDownIcon'];
const chevronUpComponent = Heroicons['ChevronUpIcon'];
</script>
