<template>
    <header id="top-mobile-bar">
        <ApplicationLogo />
        <button type="button" class="btn btn-outline-light"  @click="store.commit('navigation/toggleMobileNav')">
            <Bars3Icon />
        </button>
    </header>
</template>

<script setup>

import ApplicationLogo from "@/Components/ui/ApplicationLogo.vue";
import {useStore} from "vuex";
import {Bars3Icon} from '@heroicons/vue/24/outline';
const store = useStore();
</script>
