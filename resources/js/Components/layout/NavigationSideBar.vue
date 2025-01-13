<template>
    <div id="backDropNav" ref="backDropNav" :class="backDropClass"
         @click="store.commit('navigation/closeMobileNav')"></div>
    <aside id="navBar" :class="asideClass">
        <div id="" class="aside-header px-2">
            <Link @click.prevent="toogleSubMenu(null)" :href="route('dashboard')">
                <ApplicationLogoHrWhite/>
            </Link>
        </div>
        <nav class="px-2">
            <ul class="list-unstyled mb-0">
                <li :class="{'active' : $page.component.startsWith('Dashboard')}">
                    <NavLink
                        icon="HomeIcon"
                        :href="route('dashboard')"
                        @click.prevent="toogleSubMenu(null)"
                    >
                        Accueil
                    </NavLink>
                </li>

                <li v-if="page.props.auth.user?.projects.length" :class="{'active' : $page.component.startsWith('SingleProject/SingleProjectIndex') || currentSubNavViewed==='projects'}">
                    <NavLinkBtn icon="FolderIcon"
                                @click.prevent="toogleSubMenu('projects')"
                                :opened="currentSubNavViewed==='projects'">
                        Mes projets
                    </NavLinkBtn>

                    <TransitionExpand key="transition-projects" :duration=".2"
                                      :no-duration-on-mounted="page.component.startsWith('Projects')|| currentSubNavViewed==='projects'">
                        <div v-show="currentSubNavViewed==='projects'">
                            <ul class="list-unstyled mb-0 pb-2">
                                <li v-for="project in page.props.auth.user.projects"
                                    :key="project.id"
                                    :class="subMenuClass(project)">
                                    <SubNavLink :href="route('projects.show', project.slug)">
                                        {{ project.name }}
                                    </SubNavLink>
                                </li>
                            </ul>
                        </div>
                    </TransitionExpand>

                </li>
                <li v-if="hasRole('admin')" :class="{'active' : page.component.startsWith('Settings') || currentSubNavViewed==='settings'}">
                    <NavLinkBtn icon="Cog6ToothIcon"
                                @click.prevent="toogleSubMenu('settings')"
                                :opened="currentSubNavViewed==='settings'">
                        Paramètres
                    </NavLinkBtn>
                    <TransitionExpand key="transition-settings" :duration=".2"
                                      :no-duration-on-mounted="page.component.startsWith('Settings')|| currentSubNavViewed==='settings'">
                        <div v-show="currentSubNavViewed==='settings'">
                            <ul class="list-unstyled mb-0 pb-2">
                                <li v-if="hasPermission('manage-users')" :class="{'active' : page.component.startsWith('Settings/Users') }">
                                    <SubNavLink :href="route('settings.users.index')">Utilisateurs</SubNavLink>
                                </li>
                                <li v-if="hasPermission('manage-projects')" :class="{'active' : page.component.startsWith('Settings/Projects') }">
                                    <SubNavLink :href="route('settings.projects.index')">Gestions des projets</SubNavLink>
                                </li>
                            </ul>
                        </div>
                    </TransitionExpand>

                </li>
            </ul>

        </nav>
        <div class="mt-auto px-3 pb-3 position-relative">
            <UserMenu/>
        </div>
    </aside>
</template>

<script setup>

import {useStore} from 'vuex'
import {computed, onMounted, onUnmounted, ref, watch} from "vue";
import {debounce} from "lodash";
import NavLink from "@/Components/ui/NavLink.vue";
import TransitionExpand from "@/Components/transitions/TransitionExpand.vue";

const store = useStore()
import SubNavLink from "@/Components/ui/SubNavLink.vue";
import NavLinkBtn from "@/Components/ui/NavLinkBtn.vue";
import {Link, usePage} from '@inertiajs/vue3';
import ApplicationLogoHrWhite from "@/Components/ui/ApplicationLogoHrWhite.vue";
import UserMenu from "@/Components/ui/user/UserMenu.vue";
import {hasPermission, hasRole} from "@/Helpers/users.js";


const showMobileNav = computed(() => store.getters['navigation/isMobileNavigationShowed'])
const asideClass = computed(() => showMobileNav.value ? 'show' : '')
const backDropClass = computed(() => showMobileNav.value ? 'd-block show' : 'd-none')

const page = usePage();
const backDropNav = ref(null);
let intervalId = null;

const currentSubNavViewed = computed(() => store.getters['navigation/currentSubNavViewed']);

const subMenuClass = (project) => page.url.startsWith(`/projets/${project.slug}`) ? 'active' : '';

const toogleSubMenu = (submenu) => {
    if (currentSubNavViewed.value === submenu) {
        store.commit('navigation/updateCurrentSubNavViewed', null);
    } else {
        store.commit('navigation/updateCurrentSubNavViewed', submenu)
    }
}

/**
 * Watch the mobile nav toggle :
 * Show/hide the backDropNav and change its opacity
 * Toggle mobile-nav-opened body class
 */
watch(showMobileNav, (newValue) => {
    clearTimeout(intervalId);
    intervalId = null;
    if (newValue) {
        document.body.classList.add("mobile-nav-opened")
        backDropNav.value.classList.replace('d-none', 'd-block')
        requestAnimationFrame(() => {
            backDropNav.value.classList.add('show');
        })
    } else {
        document.body.classList.remove("mobile-nav-opened")
        backDropNav.value.classList.remove('show');
        intervalId = setTimeout(() => {
            backDropNav.value?.classList.replace('d-block', 'd-none');
        }, 300);
    }
})

/**
 * Close the nav when resizing windows
 * @type {DebouncedFunc<(function(): void)|*>}
 */
const updateWindowSize = debounce(() => {
    if (window.innerWidth >= 992 && showMobileNav.value) {
        store.commit('navigation/closeMobileNav')
    }
}, 200);

onMounted(() => {
    window.addEventListener('resize', updateWindowSize);
    if (page.component.startsWith('SingleProject/SingleProjectIndex')) {
        store.commit('navigation/updateCurrentSubNavViewed', 'projects')
    }
    if (page.component.startsWith('Settings')) {
        store.commit('navigation/updateCurrentSubNavViewed', 'settings')
    }
})

onUnmounted(() => {
    window.removeEventListener('resize', updateWindowSize);
})
</script>
