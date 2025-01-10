// store/modules/users_managment.js
export default {
    namespaced: true,
    state: {
        bugResponseToDelete: null,
        bugToDelete: null,
        fileToDelete: null,
    },
    mutations: {
        setBugResponseToDelete(state, item) {
            state.bugResponseToDelete = item;  // Change la valeur de showMobileNav
        },
        setBugToDelete(state, item) {
            state.bugToDelete = item;  // Change la valeur de showMobileNav
        },
        setFileToDelete(state, item) {
            state.fileToDelete = item;  // Change la valeur de showMobileNav
        },
    },
    getters: {
        bugResponseToDelete(state) {
            return state.bugResponseToDelete;
        },
        bugToDelete(state) {
            return state.bugToDelete;
        },
        fileToDelete(state) {
            return state.fileToDelete;
        },
    }
};
