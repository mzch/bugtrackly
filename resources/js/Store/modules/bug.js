// store/modules/users_managment.js
export default {
    namespaced: true,
    state: {
        bugToUpdateStatus: null,
        bugToUpdatePriority: null,
        bugResponseToDelete: null,
        bugToDelete: null,
    },
    mutations: {
        setBugToUpdateStatus(state, item) {
            state.bugToUpdateStatus = item;  // Change la valeur de showMobileNav
        },
        setBugToUpdatePriority(state, item) {
            state.bugToUpdatePriority = item;  // Change la valeur de showMobileNav
        },
        setBugResponseToDelete(state, item) {
            state.bugResponseToDelete = item;  // Change la valeur de showMobileNav
        },
        setBugToDelete(state, item) {
            state.bugToDelete = item;  // Change la valeur de showMobileNav
        },
    },
    getters: {
        bugToUpdateStatus(state) {
            return state.bugToUpdateStatus;
        },
        bugToUpdatePriority(state) {
            return state.bugToUpdatePriority;
        },
        bugResponseToDelete(state) {
            return state.bugResponseToDelete;
        },
        bugToDelete(state) {
            return state.bugToDelete;
        },
    }
};
