// Store/modules/navigation.js
export default {
    namespaced: true,
    state: {
        showMobileNav: false,
        currentSubNavViewed: null,
    },
    // synchrone
    mutations: {
        toggleMobileNav(state) {
            state.showMobileNav = !state.showMobileNav;  // Change la valeur de showMobileNav
        },
        openMobileNav(state) {
            state.showMobileNav = true;  // Change la valeur de showMobileNav
        },
        closeMobileNav(state) {
            state.showMobileNav = false;  // Change la valeur de showMobileNav
        },
        updateCurrentSubNavViewed(state, newSubNavViewed) {
            state.currentSubNavViewed = newSubNavViewed;  // Change la valeur de showMobileNav
        },
    },
    //asynchrone
    actions: {

    },
    getters: {
        isMobileNavigationShowed(state) {
            return state.showMobileNav;
        },
        currentSubNavViewed(state){
            return state.currentSubNavViewed
        },
    }
};
