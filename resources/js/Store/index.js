import { createStore } from 'vuex';
import navigation from './modules/navigation.js'
export default createStore({
    modules: {
        navigation,
    }
});
