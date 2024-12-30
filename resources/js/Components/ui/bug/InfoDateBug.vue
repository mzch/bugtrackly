<template>
    <p class="mb-0">
        <template v-if="no_modified">Publié</template>
        <template v-else>Dernière modification</template>
        <br>
        le {{ formatDate(bug.updated_at, "d MMMM yyyy à HH'h'mm") }}
    </p>
</template>

<script setup>
import {formatDate} from "@/Helpers/date.js";
import {computed} from "vue";

const props = defineProps({
    bug: {
        type: Object,
        required: true,
    }
})
const no_modified = computed(() => props.bug.created_at === props.bug.updated_at)
</script>

<style scoped>

</style>
