<template>
<Card :card-title="card_title" class="mb-4">
    <template #cardHeaderAction>
        <div class="col-auto ms-auto">
            <BadgePriorityBug :add-priority-label="true" :bug="bug"/>
        </div>
    </template>
    <template #cardFooter>
        <div>
            <SecondaryButton type="button" :disabled="editing_bug_description" class="btn-sm me-2"
                             @click="editing_bug_description = true" v-if="canModifyBug">
                Modifier le bug
            </SecondaryButton>
            <SecondaryButton type="button" :disabled="editing_bug_description" class="btn-sm me-2"
                             @click="store.commit('bug/setBugToUpdateStatus', props.bug)"
                             v-if="canUpdateBugStatus">
                Modifier le statut
            </SecondaryButton>
            <SecondaryButton type="button" :disabled="editing_bug_description" class="btn-sm me-2"
                             @click="store.commit('bug/setBugToUpdatePriority', props.bug)">
                Modifier la priorité
            </SecondaryButton>
            <DangerButton :disabled="editing_bug_description" class="btn-sm"
                          v-if="canDeleteBug"
                          @click="store.commit('bug/setBugToDelete', props.bug)">
                Supprimer ce bug
            </DangerButton>
        </div>
    </template>
    <div class="text-secondary mb-3" v-if="bug.user">
        <div class="d-flex align-items-center">
            <Avatar :user="bug.user" class="bordered me-1"/>
            <span class="fw-semibold me-1">{{ bug.user.full_name }}</span>
        </div>
    </div>
    <p v-if="!editing_bug_description" v-html="format_text(first_bug_comment.content)"></p>
    <template v-else>
        <FormField class="form-floating">
            <TextInput v-model="form.title"
                       class="form-control-lg"
                       :class="{'is-invalid' :form.errors.title}"
                       placeholder="Titre du bug"
                       autofocus
                       required
                       maxlength="255"/>
            <InputLabel for="bug_title" value="Titre du bug"/>
            <InputError :message="form.errors.title"/>
        </FormField>
        <FormField class="form-floating">
                    <TextArea
                        id="bug_desc"
                        placeholder="Description"
                        v-model.trim="form.content"
                        required
                        style="height: 200px"
                        :class="{'is-invalid' :form.errors.content}"/>
            <InputLabel for="bug_desc" value="Description"/>
            <InputError :message="form.errors.content"/>
        </FormField>
        <div class="mb-3">
            <SecondaryButton class="btn-sm me-2"
                             type="button"
                             outlined
                             @click="cancelEditingBugHandler">
                Annuler
            </SecondaryButton>
            <PrimaryButton class="btn-sm"
                           :disabled="!form.isDirty || form.processing"
                           @click="editBugHandler">Valider</PrimaryButton>
        </div>
    </template>
    <p class="text-sm text-secondary mb-0">{{ formatDate(bug.created_at, "d MMMM yyyy à HH'h'mm") }}</p>
</Card>
</template>

<script setup>

import Card from "@/Components/ui/Card.vue";
import {computed, ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import BadgePriorityBug from "@/Components/ui/bug/BadgePriorityBug.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import InputLabel from "@/Components/ui/form/InputLabel.vue";
import PrimaryButton from "@/Components/ui/form/PrimaryButton.vue";
import InputError from "@/Components/ui/form/InputError.vue";
import FormField from "@/Components/ui/form/FormField.vue";
import TextInput from "@/Components/ui/form/TextInput.vue";
import TextArea from "@/Components/ui/form/TextArea.vue";
import SecondaryButton from "@/Components/ui/form/SecondaryButton.vue";
import {forEach} from "lodash";
import {formatDate} from "@/Helpers/date.js";
import {useStore} from "vuex";
import {hasRole} from "@/Helpers/users.js";
import {format_text} from "@/Helpers/bug.js";
import DangerButton from "@/Components/ui/form/DangerButton.vue";
const store = useStore();
const props = defineProps({
    bug: {
        type: Object,
        required: true,
    },
    project: {
        type: Object,
        required: true,
    },
})
const first_bug_comment = computed(() => props.bug.bug_comments[0] ?? false);

const form = useForm({
    title: props.bug.title,
    content: first_bug_comment.value.content,
})

const editing_bug_description = ref(false);

const card_title = computed(() => {
    if(!editing_bug_description){
        return props.bug.title;
    }else{
        return form.title;
    }
})

const canModifyBug = computed(() => hasRole('admin'));
const canDeleteBug = computed(() => hasRole('admin'));
const canUpdateBugStatus = computed(() => {
    // un admin voir toujours ce bouton
    if (hasRole('admin')) {
        return true;
    }
    // un reporter ne voit ce bouton que si le bug est Rejeté (3), Résolu (5) ou Fermé (6)
    const status_for_reporter = [3, 5, 6]
    return status_for_reporter.includes(props.bug.status);
})
const cancelEditingBugHandler = () => {
    editing_bug_description.value = false;
    form.reset();
}
const editBugHandler = () => {
    axios.put(route('projects.bug.update', [props.project.slug, props.bug.id]), {
        title: form.title,
        content: form.content,
    })
        .then(response => {
            router.reload({
                only: ['bug'],
                onSuccess:() => {

                    cancelEditingBugHandler();
                    form.defaults({
                        title: props.bug.title,
                        content: first_bug_comment.value.content,
                    })
                    form.reset();
                },
            })
        })
        .catch((er) => {
            forEach(er.response.data.errors, (value, key) =>  form.setError(key, value[0]))
        })
}
</script>
