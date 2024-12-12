<template>
  <NewAuthenticatedLayout page-title="Dashboard">
    <template #header>Tableau de bord</template>
<!--    <template #headerActions>Actions</template>-->
      <Card class="mb-4">
          <table class="table table-bordered">
              <thead>
              <tr>
                  <th>Taille</th>
                  <th>Avatar</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                  <td>Sans taille <code>(size-2)</code></td>
                  <td>
                      <div class="d-flex">
                          <Avatar :user="current_user" class="me-2"/>
                          <Avatar :user="{profile_photo_url:null, initiales:'LGM'}"/>
                      </div>
                  </td>
              </tr>
              <tr>
                  <td>size-3</td>

                  <td>
                      <div class="d-flex">
                          <Avatar :user="current_user" class="me-2 size-3"/>
                          <Avatar :user="{profile_photo_url:null, initiales:'LGM'}" class="size-3"/>
                      </div>
                  </td>
              </tr>
              <tr>
                  <td>size-4</td>

                  <td>
                      <div class="d-flex">
                          <Avatar :user="current_user" class="me-2 size-4"/>
                          <Avatar :user="{profile_photo_url:null, initiales:'LGM'}" class="size-4"/>
                      </div>
                  </td>
              </tr>
              </tbody>
          </table>


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
import Avatar from "@/Components/ui/user/avatar.vue";
import {usePage} from "@inertiajs/vue3";
const current_user = usePage().props.auth.user;
</script>
