<template>
    <AuthenticatedLayout :page-title="project.name">
        <template #header>{{ project.name }} - <span class="text-secondary">{{ project.short_desc }}</span></template>
        <template #headerActions>
            <button class="btn btn-primary btn-with-icon btn-sm">
                <PlusCircleIcon class="size-1 me-1"/>
                Rapporter un nouveau bug
            </button>
        </template>
        <Card card-title="Liste des bugs" :remove-body-padding="true" class="mb-4">
            <template #cardFooter>
                <Pagination :items="bugs" item-singular-name="bug" item-plural-name="bugs"/>
            </template>
            <table class="table table-bordered table-hover mb-0 caption-top" v-if="bugs.data.length">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Priorité</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="bug in bugs.data" :key="bug.id">
                        <td>
                            <p class="mb-0 d-flex flex-column align-items-start">
                                <a href="#" class="fw-bold">
                                    <span class="badge text-bg-light fw-light">
                                    {{formatBugId(bug.id)}}
                                    </span>
                                    {{bug.title}}
                                </a>
                                <BagdeStatusBug class="mt-1" :bug="bug"/>

                            </p>
                        </td>
                        <td><BadgePriorityBug :bug="bug"/></td>
                        <td class="text-sm text-secondary"><InfoDateBug :bug="bug"/></td>
                    </tr>
                </tbody>
            </table>
            <div class="p-5" v-else>
                <p class="mb-0 text-center">{{ no_result }}</p>
            </div>
        </Card>
    </AuthenticatedLayout>

</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import InfoDateBug from "@/Components/ui/bug/InfoDateBug.vue";
import {formatBugId} from "../../Helpers/bug.js";
import BadgePriorityBug from "@/Components/ui/bug/BadgePriorityBug.vue";
import BagdeStatusBug from "@/Components/ui/bug/BagdeStatusBug.vue";
import Pagination from "@/Components/ui/Pagination.vue";
const props = defineProps({
    project:{
        type:Object,
        required:true,
    },
    bugs:{
        type:Object,
        required:true,
    },
    bug_status:{
        type:Array,
        required:true,
    },
    bug_priorities:{
        type:Array,
        required:true,
    }
})

/**
 * Filter received from the controller
 * @type {ComputedRef<unknown>}
 */
const filters = computed(() => usePage().props.filters);
const no_result = computed( () => filters.value.search !== null ? "Aucun bug trouvé" : "Aucun bug enregistré")
</script>

<style scoped>

</style>
