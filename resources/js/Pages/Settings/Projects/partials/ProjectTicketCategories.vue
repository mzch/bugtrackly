<template>
<Card card-title="Catégories des tickets">
    <p class="text-secondary" :class="{'mb-0': !form.ticket_categories.length}">
        Les catégories de ticket sont un moyen supplémentaire de trier ces derniers.
        Vous pouvez créer autant de catégories que vous le souhaitez.
    </p>
    <table class="mb-0 table table-bordered table-hover" v-if="form.ticket_categories.length">
        <draggable v-model="form.ticket_categories"
                   item-key="index"
                   tag="tbody"
                   handle=".handle-drag"
                   ghost-class="ghost"
                   @end="reorder_data"
                   :class="{'drag-disabled': indexCatEditing !== null, 'drag-enabled': indexCatEditing === null}"
                   :disabled="indexCatEditing !== null">
            <template #item="{element, index}">
                <tr>
                    <td class="align-middle">
                        <TicketCategoryEdit :index="index"
                                            :editing-index="indexCatEditing"
                                            v-model="form.ticket_categories[index]"
                                            @click-delete-category="i => removeCategory(i)"
                                            @click-edit="i => editCategory(i)"
                                            @click-end-editing="i => endEditCategory(i)"/>
                    </td>
                </tr>
            </template>
        </draggable>
    </table>

    <template #cardFooter>
        <div class="row">
            <div class="col-auto">
                <div class="input-group">
                    <TextInput v-model.trim="newCategoryName" @keydown.enter.prevent="addCategory" />
                    <PrimaryButton type="button" @click.prevent="addCategory" :disabled="newCategoryName===''" :outlined="true">Ajouter la catégorie</PrimaryButton>
                </div>
            </div>
        </div>
    </template>
</Card>
</template>

<script setup>
import draggable from 'vuedraggable'
import Card from "@/Components/ui/Card.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import {ref} from "vue";
import TicketCategoryEdit from "@/Pages/Settings/Projects/partials/form/TicketCategoryEdit.vue";
import {map} from "lodash";
const newCategoryName = ref("");
const indexCatEditing = ref(null);
const props = defineProps({
    form: {
        type: Object,
        required: true
    }
})

const reorder_data = () => {
    props.form.ticket_categories = map(props.form.ticket_categories, (item, index) => ({...item, order:index}))
}

const addCategory = (event) => {
    if(newCategoryName.value !== '' && !event.isComposing){
        const order = props.form.ticket_categories.length;
        props.form.ticket_categories.push({order, name:newCategoryName.value});
        newCategoryName.value = '';
    }
}

const editCategory = (index) => {
    indexCatEditing.value = index;
    console.log(`J'édite la cat ${index}`);
}
const endEditCategory = (index) => {
    indexCatEditing.value = null;
    console.log(`J'ai fini la cat ${index}`);
}
const removeCategory = (index) => {
    if (index >= 0 && index < props.form.ticket_categories.length) {
        props.form.ticket_categories.splice(index, 1);
        reorder_data();
    }
}
</script>
<style>
tbody.drag-enabled{
    tr{
        td{
            .handle-drag{
                cursor: grab;
            }
        }
        &.ghost {
            td {
                .handle-drag {
                    cursor: grabbing;
                }
            }
        }
    }
}

.ghost {
    opacity: 0.5;
    background: #c8ebfb;
}

</style>
