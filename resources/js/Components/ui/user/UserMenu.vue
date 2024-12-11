<template>
    <div v-show="showUserMenu">
        <div id="user-menu">
            <div class="header d-flex align-items-center">
                <Avatar class="active size-2 me-2 flex-shrink-0" :user="$page.props.auth.user"/>
                <div class="identity text-secondary lh-sm">
                    <span class="name text-white">{{ $page.props.auth.user.full_name }}</span>
                    <br class="visually-hidden">
                    <small class="email">{{ $page.props.auth.user.email }}</small>
                </div>
            </div>
            <ul class="list-unstyled">
                <li>
                    <NavLink icon="UserCircleIcon"
                             :href="route('profile.edit')">
                        Mon profil
                    </NavLink>
                </li>
            </ul>
            <ul class="list-unstyled">
                <li>
                    <Link as="button" class="btn btn-link" method="post" :href="route('logout')">Se déconnecter</Link>
                </li>
            </ul>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center" ref="root">
        <button class="btn btn-link p-0" @click="showUserMenu = !showUserMenu">
            <Avatar class="size-2" :user="$page.props.auth.user"/>
        </button>
        <Link as="button" method="post" class="btn btn-link text-gray px-0" :href="route('logout')">
            <ArrowRightStartOnRectangleIcon style="width: 1.125rem; height: 1.125rem;"/>
        </Link>
    </div>
</template>

<script setup>
import {ArrowRightStartOnRectangleIcon} from "@heroicons/vue/24/outline/index.js";
import NavLink from "@/Components/ui/NavLink.vue";
import Avatar from "@/Components/ui/user/avatar.vue";
import {Link} from "@inertiajs/vue3";
import {onMounted, onUnmounted, ref} from "vue";
const root = ref(null)
const showUserMenu = ref(false);
const closeUserDropDown = (e) => {
    if (! root.value.contains(e.target)) {
        showUserMenu.value = false
    }
};
onMounted(() => {
    document.body.addEventListener("click", closeUserDropDown);
});
onUnmounted( () => {
    document.body.removeEventListener("click", closeUserDropDown);
})
</script>

<style scoped>

</style>
