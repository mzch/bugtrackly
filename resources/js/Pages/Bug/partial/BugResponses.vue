<template>
    <Card card-title="Notes">
        <div>
            <BugSingleResponse v-for="(response, index) in bugResponses" :key="response.id"
                               class="mb-4" :class="{'my-card': response.user.id === current_user.id}" :response="response"/>
            <Card class="my-card">
                <div class="d-flex align-items-center text-secondary mb-3">
                    <Avatar :user="current_user" class="bordered me-1"/>
                    <span class="fw-semibold me-1">{{ current_user.full_name }}</span>
                </div>
                <FormField class="form-floating mb-2" no-margin-bottom>
                   <TextArea
                       id="bug_desc"
                       placeholder="Nouvelle note"
                       v-model.trim="form.content"
                       autofocus
                       style="height: 150px"
                       :class="{'is-invalid' :form.errors.content}"/>
                    <InputLabel for="bug_desc" value="Nouvelle note"/>
                    <InputError :message="form.errors.content"/>
                </FormField>
                <button v-if="!show_change_bug_props_form" @click="show_change_bug_props_form=true" type="button" class="btn btn-link px-0 text-sm">Changer le statut, la priorité ou l'utilisateur assigné à ce bug</button>
                <button v-else type="button" @click="cancel_show_change_bug_props_form" class="btn btn-link px-0 text-sm">Annuler</button>
                <TransitionExpand>
                    <div v-if="show_change_bug_props_form">
                        <div class="row">
                            <div class="col-md-4">
                                <FormField class="form-floating mt-4">
                                    <FormSelect select-label="Sélectionner un nouveau statut"
                                                :options="bug_status_options"
                                                :class="{'is-invalid' :form.errors.status}"
                                                v-model="form.status"/>
                                    <InputLabel for="new_bug_status" value="Nouveau statut"/>
                                    <InputError :message="form.errors.status"/>
                                </FormField>
                            </div>
                            <div class="col-md-4">
                                <FormField class="form-floating mt-4">
                                    <FormSelect select-label="Sélectionner une nouvelle priorité"
                                                :options="bug_priorities_options"
                                                :class="{'is-invalid' :form.errors.priority}"
                                                v-model="form.priority"/>
                                    <InputLabel for="new_bug_status" value="Nouvelle priorité"/>
                                    <InputError :message="form.errors.priority"/>
                                </FormField>
                            </div>
                            <div class="col-md-4">
                                <UserAvatarVSelect id="assigned_user"
                                                   label="Assigner ce bug à un utilisateur :"
                                                   v-model="form.assigned_user_id"
                                                   :users="project.users"/>

                            </div>
                        </div>
                    </div>
                </TransitionExpand>

                <div>
                    <PrimaryButton class="btn-sm"
                                   :disabled="!form.isDirty || form.processing"
                                   @click="submitResponseHandler">Ajouter ma note
                    </PrimaryButton>
                </div>
            </Card>
        </div>
    </Card>
</template>

<script setup>
import Card from "@/Components/ui/Card.vue";
import BugSingleResponse from "@/Pages/Bug/partial/BugSingleResponse.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import TextArea from "@/Components/ui/form/TextArea.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {computed, ref} from "vue";
import {forEach} from "lodash";
import UserAvatarVSelect from "@/Components/ui/user/UserAvatarVSelect.vue";
import FormSelect from "@/Components/ui/form/FormSelect.vue";
import {getStatusObject} from "@/Helpers/bug.js";
import TransitionExpand from "@/Components/transitions/TransitionExpand.vue";

const props = defineProps({
    bugResponses: {
        type: Array,
        required: true,
        defaults: []
    }
})

const show_change_bug_props_form = ref(false);
const cancel_show_change_bug_props_form = () => {
    form.defaults({
        content:'',
        priority:bug.value.priority,
        status:bug.value.status,
        assigned_user_id: bug.value.assigned_user_id,
    })
    form.isDirty = false;
    form.reset();
    show_change_bug_props_form.value = true;
}
const bug = computed(() => usePage().props.bug)
const bug_priorities = usePage().props.bug_priorities ?? [];
const bug_priorities_options = computed(() => (
    bug_priorities.map(p => {
        return {
            id: p.id,
            label: p.label
        }
    })
))
const project = computed(() => usePage().props.project)
const current_user = computed(() => usePage().props.auth.user)
const form = useForm({
    content: "",
    priority:bug.value.priority,
    status:bug.value.status,
    assigned_user_id: bug.value.assigned_user_id,
})



const current_bug_status = computed(() => getStatusObject(bug.value.status))
const bug_status_options = computed(() => {
    const current = {id:current_bug_status.value.id, label:current_bug_status.value.label};
    const available = current_bug_status.value.children.map(status => {
        return {
            id: status.id,
            label: status.label
        }
    })
    return [current,...available]
})


const submitResponseHandler = () => {
    const urlParams = route().params;
    axios.post(route('projects.bug.store-response', [urlParams.project, urlParams.bug]), {
        content: form.content,
        priority:form.priority,
        status:form.status,
        assigned_user_id: form.assigned_user_id,
    })
        .then(response => {
            router.reload({
                only: ['bug'],
                onSuccess: () => {
                    cancel_show_change_bug_props_form()
                },
            })
        })
        .catch((er) => {
            forEach(er.response.data.errors, (value, key) => form.setError(key, value[0]))
        })
}
</script>

<style scoped>

</style>
