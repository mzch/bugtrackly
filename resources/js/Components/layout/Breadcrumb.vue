<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li v-for="(breadcrump_part, index) in breadcrumb"
                :key="index"
                class="breadcrumb-item"
                :class="{'active' : index === breadcrumb.length - 1}"
                :aria-current="index === breadcrumb.length - 1 ? 'page' : null">

                <template v-if="index !== breadcrumb.length - 1">
                    <Link class="text-secondary" :href="breadcrump_part.url" >{{breadcrump_part.name}}</Link>
                </template>
                <template v-else>
                    {{ breadcrump_part.name }}
                </template>
            </li>
        </ol>
    </nav>
</template>

<script setup>
import {usePage} from "@inertiajs/vue3";
import { Link } from '@inertiajs/vue3';
const breadcrumb = usePage().props.breadcrumb;
</script>
