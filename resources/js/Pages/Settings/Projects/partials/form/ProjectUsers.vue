<template>
    <Card card-title="Utilisateurs du projet">
        <div class="row">
            <div class="col-lg-6">
                <h3>Utilisateurs pour ce projet :</h3>
                <ul class="mb-O list-inline">
                    <li class="list-inline-item" v-for="user in user_for_project" :key="user.id">
                        <ButtonUserAvatar :user="user" @click="remove_user(user.id)"/>
                    </li>
                </ul>

            </div>
            <div class="col-lg-6">
                <h3>Liste des utilisateurs</h3>
                <ul class="mb-0 list-unstyled">
                    <li class="list-inline-item" v-for="user in all_users" :key="user.id">
                        <ButtonUserAvatar :user="user"
                                          :disabled="is_button_disabled(user.id)"
                                          @click="add_user(user.id)"/>
                    </li>
                </ul>
            </div>
        </div>
    </Card>
</template>

<script setup>
import Card from "@/Components/ui/Card.vue";
import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import {find} from "lodash";
import ButtonUserAvatar from "@/Components/ui/form/ButtonUserAvatar.vue";

const all_users = computed(()=> usePage().props.users);
const props = defineProps({
    form: {
        type: Object,
        required: true
    }
})

const user_for_project = computed(() => {
    return props.form.users.map(u => find(all_users.value, user => user.id === u))
})

const add_user = (id) => props.form.users.push(id)
const remove_user = (idToRemove) =>  props.form.users = props.form.users.filter(id => id !== idToRemove);


const is_button_disabled = (id) => props.form.users.includes(id)
</script>
<style scoped>

</style>
