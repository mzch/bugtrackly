<template>
    <Card card-title="Notes">
        <div>
            <BugSingleResponse v-for="(response, index) in bugResponses" :key="response.id"
                               class="mb-4" :response="response"/>
            <Card>
                <div class="d-flex align-items-center text-secondary mb-3">
                    <Avatar :user="current_user" class="bordered me-1"/>
                    <span class="fw-semibold me-1">{{ current_user.full_name }}</span>
                </div>
               <FormField class="form-floating">
                   <TextArea
                       id="bug_desc"
                       placeholder="Nouvelle note"
                       v-model.trim="form.content"
                       required
                       style="height: 150px"
                       :class="{'is-invalid' :form.errors.content}"/>
                   <InputLabel for="bug_desc" value="Nouvelle note"/>
                   <InputError :message="form.errors.content"/>
               </FormField>
                <div>
                    <PrimaryButton class="btn-sm"
                                   :disabled="!form.isDirty || form.processing"
                                   @click="submitResponseHandler">Ajouter ma note</PrimaryButton>
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
import {computed} from "vue";
import {forEach} from "lodash";

const props = defineProps({
    bugResponses:{
        type:Array,
        required:true,
        defaults:[]
    }
})
const current_user = computed(() => usePage().props.auth.user)
const form = useForm({
    content:"",
})

const submitResponseHandler = () => {
    const urlParams = route().params;
    axios.post(route('projects.bug.store-response', [urlParams.project, urlParams.bug]), {
        content: form.content,
    })
        .then(response => {
            router.reload({
                only: ['bug'],
                onSuccess:() => form.reset(),
            })
        })
        .catch((er) => {
            forEach(er.response.data.errors, (value, key) =>  form.setError(key, value[0]))
        })
}
</script>

<style scoped>

</style>
