import {Tooltip} from "bootstrap";

const enableToolTips = (tooltipList) => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    tooltipList.value = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl))
}
const disposeToolTips = (tooltipList) => {
    tooltipList.value.forEach(tooltip => {
        tooltip.dispose();
    });
}
export {enableToolTips,disposeToolTips}
