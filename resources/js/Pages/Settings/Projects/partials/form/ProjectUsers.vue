<template>
    <Card :card-title="trans('settings.projects.form.users_title')">
        <div class="row">
            <div class="col-lg-6">
                <h5>{{ trans('settings.projects.form.admin_label') }}</h5>
                <ul class="mb-O list-inline" v-if="admin_for_project.length">
                    <li class="list-inline-item" v-for="user in admin_for_project" :key="user.id">
                        <ButtonUserAvatar :user="user" class="mb-1" @click="remove_user(user.id)"/>
                    </li>
                </ul>
                <p v-else class="text-secondary text-sm">{{ trans('settings.projects.form.no_admin_alert') }}</p>
                <h5>{{ trans('settings.projects.form.reporter_label') }}</h5>
                <ul class="mb-O list-inline" v-if="reporter_for_project.length">
                    <li class="list-inline-item" v-for="user in reporter_for_project" :key="user.id">
                        <ButtonUserAvatar :user="user" class="mb-1" @click="remove_user(user.id)"/>
                    </li>
                </ul>
                <p v-else class="text-secondary text-sm">{{ trans('settings.projects.form.no_reporter_alert') }}</p>
                <p class="text-secondary text-sm mb-0" v-if="admin_for_project.length || reporter_for_project.length">
                    {{trans('settings.projects.form.user_remove_help')}}
                </p>
            </div>
            <div class="col-lg-6">
                <h5>{{ trans('settings.users.list_title') }}</h5>

                <UserAvatarVSelect
                    :label="trans('settings.projects.form.select_user')"
                    id="vs-list-users"
                    v-model="user_selected"
                    :users="all_users"
                    :selectableCondition="selectableUser"/>
            </div>
        </div>
    </Card>
</template>

<script setup>
import Card from "@/Components/ui/Card.vue";
import {usePage} from "@inertiajs/vue3";
import {computed, nextTick, ref, watch} from "vue";
import {find} from "lodash";
import ButtonUserAvatar from "@/Components/ui/form/ButtonUserAvatar.vue";
import UserAvatarVSelect from "@/Components/ui/user/UserAvatarVSelect.vue";
import {trans} from "@/Helpers/translations.js";

const all_users = computed(()=> usePage().props.users);
const props = defineProps({
    form: {
        type: Object,
        required: true
    }
})
const user_selected = ref(null);

const user_for_project = computed(() => props.form.users.map(u => find(all_users.value, user => user.id === u)))
const admin_for_project = computed(() => user_for_project.value.filter(u => u.role_id === 1));
const reporter_for_project = computed(() => user_for_project.value.filter(u => u.role_id === 2));

const remove_user = (idToRemove) =>  props.form.users = props.form.users.filter(id => id !== idToRemove);

// For a user to be selectable, it must not be in the props.form.users list.
const selectableUser = (user) => !props.form.users.includes(user.id);

watch(user_selected, (newUser, oldUser) => {
    if(newUser){
        props.form.users.push(newUser);
        nextTick(() => {
            user_selected.value = null;
        })
    }
})
</script>
<style scoped>

</style>
