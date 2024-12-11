<template>
  <NewAuthenticatedLayout page-title="Dashboard">
    <template #header>Tableau de bord</template>
<!--    <template #headerActions>Actions</template>-->
      <Card class="mb-4">
          
          <Avatar class="size-2" :user="user"/>
          <br>
          <Avatar class="size-3" :user="user"/>
          <br>
          <Avatar class="size-2" :user="{profile_photo_url:null, initiales:'LG'}"/>
          <br>
          <Avatar class="size-3" :user="{profile_photo_url:null, initiales:'LG'}"/>
          <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium autem debitis iure libero nesciunt,
              obcaecati
              pariatur quia tenetur! Accusantium aperiam cum dicta odio reprehenderit! Ab enim excepturi ipsam obcaecati
              velit!</p>
      </Card>
      <div class="row">
          <div class="col-sm-4">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium autem debitis iure libero nesciunt,
                  obcaecati
                  pariatur quia tenetur! Accusantium aperiam cum dicta odio reprehenderit! Ab enim excepturi ipsam obcaecati
                  velit!</p>
          </div>
          <div class="col-sm-4">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis cupiditate esse eveniet, itaque
                  laboriosam omnis placeat ratione sapiente temporibus unde! Ab alias, aspernatur corporis doloribus
                  ipsum odit quis quos tempore!</p>
          </div>
          <div class="col-sm-4">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus consectetur cumque debitis deleniti
                  dolor dolorum earum est explicabo fuga mollitia natus, perferendis quisquam ratione soluta, tempora
                  ullam voluptatem. Asperiores, earum.</p>
          </div>
      </div>

  </NewAuthenticatedLayout>
</template>

<script setup>
import NewAuthenticatedLayout from '@/Layouts/NewAuthenticatedLayout.vue';
import Card from "@/Components/ui/Card.vue";
import {usePage} from "@inertiajs/vue3";
import Avatar from "@/Components/ui/user/avatar.vue";
import {ArrowRightStartOnRectangleIcon} from "@heroicons/vue/24/outline/index.js";
const user = usePage().props.auth?.user;
</script>
