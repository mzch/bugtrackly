import { createStore } from 'vuex';
import navigation from './modules/navigation.js'
import usersManagement from './modules/users_management.js'
export default createStore({
    modules: {
        navigation,
        usersManagement
    }
});
