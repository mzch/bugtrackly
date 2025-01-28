import { format } from 'date-fns'
import {fr, enUS} from 'date-fns/locale'
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const formatDate = (date, formatStr='d MMMM yyyy') => {
    let selectedLocale;
    const locale = computed(() => usePage().props.current_locale)
    switch (locale.value){
        case 'fr' :
            selectedLocale = fr;
            break;
        case 'en' :
        default :
            selectedLocale = enUS;
    }
    return format(new Date(date), formatStr, {locale:selectedLocale})
}

export {formatDate}
