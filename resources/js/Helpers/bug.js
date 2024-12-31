import {usePage} from "@inertiajs/vue3";
import {find} from "lodash";

const formatBugId = (id) => {
    return `${id.toString().padStart(7, '0')}`;
}

const getPriorityObject = (id) => {
    const priorities = usePage().props.bug_priorities ?? false;
    if(!priorities){
        console.error("Veuillez passer la liste de priorité des bugs à la page.");
        return false;
    }
    const priority = find(priorities, item => item.id === id);
    if(!priority){
        return false;
    }
    return priority
}

const getStatusObject = (id) => {
    const bug_status = usePage().props.bug_status ?? false;
    if(!bug_status){
        console.error("Veuillez passer la liste de status disponible des bugs à la page.");
        return false;
    }
    const status = find(bug_status, item => item.id === id);
    if(!status){
        return false;
    }
    return status
}

export {formatBugId, getPriorityObject, getStatusObject}
