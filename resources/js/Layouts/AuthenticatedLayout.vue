<template>
  <Head :title="pageTitle"/>
  <MobileTopBar/>
  <div id="main-wrapper">
    <NavigationSideBar/>
    <div id="content-wrapper">
      <div class="container-lg px-4 d-flex flex-column h-100">
        <header class="mt-4 mt-lg-5 d-flex align-items-center mb-3">
            <div class="flex-grow-1">
                <h1 class="fs-4 fw-semibold mb-@1">
                    <slot name="header">&lt;template #header&gt;</slot>
                </h1>
                <Breadcrumb/>
            </div>
            <template v-if="$slots.headerActions">
                <slot name="headerActions"/>
            </template>
        </header>
        <main class="flex-grow-1 mt-2">
          <slot/>
        </main>
        <footer class="mt-auto text-secondary py-3 text-end">
          <small>
              <span class="opacity-75" v-if="usePage().props.baseline">{{usePage().props.baseline}} -</span>
              © 2024 BugTrackly - Version {{usePage().props.app_version}}
          </small>
        </footer>
      </div>
    </div>
  </div>
</template>

<script setup>
import NavigationSideBar from "@/Components/layout/NavigationSideBar.vue";
import MobileTopBar from "@/Components/layout/MobileTopBar.vue";
import {Head, usePage} from '@inertiajs/vue3';
import Breadcrumb from "@/Components/layout/Breadcrumb.vue";
defineProps({
  pageTitle: {
    type:String,
    required:true
  },
});
</script>
