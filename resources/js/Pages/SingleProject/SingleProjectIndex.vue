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
            <pre>{{project.bugs}}</pre>
        </Card>
        <Card card-title="Info sur les bugs">
            <div class="row">
                <div class="col-sm-6">
                    <h5>Liste des status des bugs</h5>
                    <ul>
                        <li v-for="(status, index) in bug_status" :key="index">
                            {{status.label}}
                            <ul v-if="status.children.length">
                                <li v-for="(child_status, index_child) in status.children" :key="index_child">
                                    {{child_status.label}}
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <h5>Liste des priorités des bugs</h5>
                    <ul>
                        <li v-for="(priority, index) in bug_priorities" :key="index">
                            {{priority.label}}
                        </li>
                    </ul>
                </div>
            </div>

        </Card>
    </AuthenticatedLayout>

</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/ui/Card.vue";
const props = defineProps({
    project:{
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
</script>

<style scoped>

</style>
