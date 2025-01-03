<template>
    <Card card-title="Réponses">
        <div v-if="bugResponses.length">
            <BugSingleResponse v-for="(response, index) in bugResponses" :key="response.id"
                               :class="{'mb-4' : index< bugResponses.length -1 }" :response="response"/>
        </div>
    </Card>
</template>

<script setup>
import Card from "@/Components/ui/Card.vue";
import BugSingleResponse from "@/Pages/Bug/partial/BugSingleResponse.vue";

const props = defineProps({
    bugResponses:{
        type:Array,
        required:true,
        defaults:[]
    }
})
</script>

<style scoped>

</style>
