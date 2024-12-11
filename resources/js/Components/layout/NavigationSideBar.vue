<template>
    <div id="backDropNav" ref="backDropNav" :class="backDropClass" @click="store.commit('navigation/closeMobileNav')"></div>
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

                <li :class="{'active' : $page.component.startsWith('Projects') || currentSubNavViewed==='projects'}">
                    <NavLinkBtn icon="FolderIcon"
                                @click.prevent="toogleSubMenu('projects')"
                                :opened="currentSubNavViewed==='projects'">
                        Mes projets
                    </NavLinkBtn>

                    <TransitionExpand key="transition-projects" :duration=".2"
                                      :no-duration-on-mounted="page.component.startsWith('Projects')|| currentSubNavViewed==='projects'">
                        <div v-show="currentSubNavViewed==='projects'">
                            <ul class="list-unstyled mb-0 pb-2">
                                <li :class="{'active' : page.component.startsWith('Projects/SoProtocol') }">
                                    <SubNavLink :href="route('projects-sop')">SoProtocol - App</SubNavLink>
                                </li>
                                <li :class="{'active' : page.component.startsWith('Projects/Lauraco') }">
                                    <SubNavLink :href="route('projects-loraco')">Lauraco - Site Elo</SubNavLink>
                                </li>
                            </ul>
                        </div>
                    </TransitionExpand>

                </li>
                <li :class="{'active' : page.component.startsWith('Settings') || currentSubNavViewed==='settings'}">
                    <NavLinkBtn icon="Cog6ToothIcon"
                                @click.prevent="toogleSubMenu('settings')"
                                :opened="currentSubNavViewed==='settings'">
                        Paramètres
                    </NavLinkBtn>
                    <TransitionExpand key="transition-settings" :duration=".2"
                                      :no-duration-on-mounted="page.component.startsWith('Settings')|| currentSubNavViewed==='settings'">
                        <div v-show="currentSubNavViewed==='settings'">
                            <ul class="list-unstyled mb-0 pb-2">
                                <li :class="{'active' : page.component.startsWith('Settings/Users') }">
                                    <SubNavLink :href="route('users')">Utilisateurs</SubNavLink>
                                </li>
                                <li :class="{'active' : page.component.startsWith('Settings/Projects') }">
                                    <SubNavLink :href="route('settings-project')">Gestions des projets</SubNavLink>
                                </li>
                            </ul>
                        </div>
                    </TransitionExpand>

                </li>
            </ul>

        </nav>
        <div class="mt-auto px-2 pb-5">
            <Dropdown align="right" width="48">
                <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                                >
                                                    {{ $page.props.auth.user.name }}

                                                    <svg
                                                        class="-me-0.5 ms-2 h-4 w-4"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                </button>
                                            </span>
                </template>

                <template #content>
                    <DropdownLink
                        @click.prevent="toogleSubMenu(null)"
                        :href="route('profile.edit')"
                    >
                        Profile
                    </DropdownLink>
                    <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        Log Out
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
    </aside>
</template>

<script setup>

import {useStore} from 'vuex'
import {computed, onMounted, onUnmounted, ref, watch} from "vue";
import {debounce} from "lodash";
import NavLink from "@/Components/ui/NavLink.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import TransitionExpand from "@/Components/transitions/TransitionExpand.vue";

const store = useStore()
import SubNavLink from "@/Components/ui/SubNavLink.vue";
import NavLinkBtn from "@/Components/ui/NavLinkBtn.vue";
import {Link, usePage} from '@inertiajs/vue3';
import ApplicationLogoHr from "@/Components/ui/ApplicationLogoHr.vue";
import ApplicationLogoHrWhite from "@/Components/ui/ApplicationLogoHrWhite.vue";
const showMobileNav = computed(() => store.getters['navigation/isMobileNavigationShowed'])
const asideClass = computed(() => showMobileNav.value ? 'show' : '')
const backDropClass = computed(() => showMobileNav.value ? 'd-block show' : 'd-none')

const page = usePage();
const backDropNav = ref(null);
let intervalId = null;

const currentSubNavViewed = computed(() => store.getters['navigation/currentSubNavViewed']);

const toogleSubMenu = (submenu) => {
    if (currentSubNavViewed.value === submenu) {
        store.commit('navigation/updateCurrentSubNavViewed', null)
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
            backDropNav.value.classList.replace('d-block', 'd-none');
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
    if (page.component.startsWith('Projects')) {
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
