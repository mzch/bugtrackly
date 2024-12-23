<template>
  <AuthenticatedLayout page-title="Dashboard">
    <template #header>Tableau de bord</template>
      <Card class="mb-4">
          <div class="row">
              <div class="col-lg-4" v-for="project in projects">
                  <CardProject :project="project"/>

              </div>

          </div>
      </Card>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/ui/Card.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {usePage} from "@inertiajs/vue3";
import CardProject from "@/Components/ui/project/CardProject.vue";
const current_user = usePage().props.auth.user;

const props = defineProps({
    projects:{
        type:Array,
        required:true,
    }
})
</script>
