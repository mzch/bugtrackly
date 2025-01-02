// store/modules/users_managment.js
export default {
    namespaced: true,
    state: {
        bugToUpdateStatus: null,
    },
    mutations: {
        setBugToUpdateStatus(state, item) {
            state.bugToUpdateStatus = item;  // Change la valeur de showMobileNav
        },
    },
    getters: {
        bugToUpdateStatus(state) {
            return state.bugToUpdateStatus;
        },
    }
};
