// store/modules/users_managment.js
export default {
    namespaced: true,
    state: {
        userToDelete: null,
    },
    mutations: {
        setUserToDelete(state, user) {
            state.userToDelete = user;  // Change la valeur de showMobileNav
        }
    },
    actions: {

    },
    getters: {
        userToDelete(state) {
            return state.userToDelete;
        }
    }
};
