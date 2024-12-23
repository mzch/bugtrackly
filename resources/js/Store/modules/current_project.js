// store/modules/users_managment.js
export default {
    namespaced: true,
    state: {
        currentProject: null,
    },
    mutations: {
        setCurrentProject(state, item) {
            state.currentProject = item;  // Change la valeur de showMobileNav
        },
    },
    getters: {
        currentProject(state) {
            return state.currentProject;
        },
    }
};
