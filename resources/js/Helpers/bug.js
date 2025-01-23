import {usePage} from "@inertiajs/vue3";
import {find} from "lodash";

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

const nb_notes = (nb_responses) => nb_responses - 1;
const nb_notes_labels = (bug) => {
    const nbAddtionalNotes = nb_notes(bug.bug_comments_count);
    return nbAddtionalNotes > 1 ? `${nbAddtionalNotes} notes` : `${nbAddtionalNotes} note`;

}

const bug_priority_class = (bug) => {
    const prioObj = getPriorityObject(bug.priority)
    return `text-bg-priority-${prioObj.weight}`;
}

const format_text = (text) => {
    if(!text)
        return "";
    return text.replace(/\n/g, '<br>')
}

export {getPriorityObject, getStatusObject, nb_notes,nb_notes_labels, bug_priority_class, format_text}
