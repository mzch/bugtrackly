// store/modules/users_managment.js
export default {
    namespaced: true,
    state: {
        projectToDelete: null,
    },
    mutations: {
        setProjectToDelete(state, item) {
            state.projectToDelete = item;  // Change la valeur de showMobileNav
        },
    },
    getters: {
        projectToDelete(state) {
            return state.projectToDelete;
        },
    }
};
