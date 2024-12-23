import { createStore } from 'vuex';
import navigation from './modules/navigation.js'
import usersManagement from './modules/users_management.js'
import projectsManagement from './modules/projects_management.js'
import currentProject from './modules/current_project.js'
export default createStore({
    modules: {
        navigation,
        usersManagement,
        projectsManagement,
        currentProject
    }
});
