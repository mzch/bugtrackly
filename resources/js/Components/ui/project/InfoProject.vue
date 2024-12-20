<template>
    <p class="mb-0">
        <template v-if="no_modified">Publié</template>
        <template v-else>Dernière modification</template>
        <br>
        le {{ formatDate(project.updated_at, "d MMMM yyyy à HH'h'mm") }}
    </p>
</template>

<script setup>
import {computed} from "vue";
import {formatDate} from "../../../Helpers/date.js";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    }
})

const no_modified = computed(() => props.project.created_at === props.project.updated_at)
</script>
