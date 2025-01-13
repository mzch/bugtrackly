<template>
    <p class="fw-semibold mb-0">
        <template v-if="comment.files.length > 1">Fichiers liés</template>
        <template v-else>Fichier lié</template>
    </p>
    <ul class="list-group mt-1 mb-0">
        <li v-for="file in comment.files" :key="file.id" class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <a :href="route('projects.bug.download_file', [project.slug, bug.id, comment.id, file.id])" target="_blank">{{getFileName(file.file_path)}}</a>
                <br>
                <span class="text-sm text-secondary">{{file.size_human_readable}}</span>
            </div>
            <button type="button"
                    @click="store.commit('bug/setFileToDelete', file)"
                    class="btn btn-sm btn-link text-danger btn-with-icon" v-if="hasRole('admin')">
                <TrashIcon class="size-1"/></button>
        </li>
    </ul>
</template>

<script setup>
    import {hasRole} from "@/Helpers/users.js";
    import {getFileName} from "@/Helpers/filename.js";
    import {TrashIcon} from "@heroicons/vue/24/outline/index.js";
    import {computed} from "vue";
    import {usePage} from "@inertiajs/vue3";
    import {useStore} from "vuex";
    const store = useStore();
    const props = defineProps({
        comment:{
          type:Object,
          required:true,
        },

    })

    const project = computed(() => usePage().props.project)
    const bug = computed(() => usePage().props.bug)
</script>

<style scoped>

</style>
