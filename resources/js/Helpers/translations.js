import {usePage} from "@inertiajs/vue3";

const trans = (key) => {
    return usePage().props.translations[key];
}
const trans_params = (key, ...values) => {
    let trad = trans(key);
    let index = 0;
    return trad.replace(/%s/g, () => values[index++]);
}
export {trans,trans_params};
