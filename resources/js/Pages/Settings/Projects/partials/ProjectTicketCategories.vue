<template>
<Card card-title="Catégorie des tickets">
    <p class="text-secondary">Les catégories de ticket sont un moyen supplémentaire de trier ces derniers. Vous pouvez créer autant de catégorie que vous le souhaitez.</p>
    <pre>{{form.ticket_categories}}</pre>
    <pre>{{newCategorieName}}</pre>
    <template #cardFooter>
        <div class="row g-3">
            <div class="col-sm-3">
                <TextInput v-model.trim="newCategorieName" @keydown.enter.prevent="pushCategory" />
            </div>
            <div class="col-sm">
                <PrimaryButton type="button" @click.prevent="pushCategory" :disabled="newCategorieName===''" :outlined="true">Ajouter la catégorie</PrimaryButton>
            </div>
        </div>


    </template>
</Card>
</template>

<script setup>
import Card from "@/Components/ui/Card.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import {ref} from "vue";

const newCategorieName = ref("");
const props = defineProps({
    form: {
        type: Object,
        required: true
    }
})

const pushCategory = () => {
    if(newCategorieName.value !== ''){
        props.form.ticket_categories.push(newCategorieName.value);
        newCategorieName.value = '';
    }

}
</script>
