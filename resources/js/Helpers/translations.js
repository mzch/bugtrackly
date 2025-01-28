import {usePage} from "@inertiajs/vue3";

const trans = (key) => {
    return usePage().props.translations[key] || key;
}

const trans_params = (key, ...values) => {
    let trad = trans(key);
    let index = 0;
    return trad.replace(/%s/g, () => values[index++]);
}
const trans_choice = (key, count) => {
    // Obtenez les traductions depuis les props d'Inertia
    const translations = usePage().props.translations || {};
    const translationString = translations[key];

    if (!translationString) {
        return key; // Si la clé n'existe pas, retournez la clé brute
    }

    // Découper les règles pluriels en parties
    const choices = translationString.split('|');

    let selectedText = '';

    // Chercher la première règle qui correspond au count
    for (let choice of choices) {
        // Vérifier si la règle correspond exactement à count, par exemple "{9}"
        const exactMatch = new RegExp(`^\\{${count}\\}`).exec(choice);
        if (exactMatch) {
            selectedText = choice.replace(`{${count}}`, '').trim(); // Enlève la balise {count}
            break;
        }

        // Vérifier les plages comme [2,*]
        const rangeMatch = new RegExp(`\\[([0-9]+),\\*\\]`).exec(choice);
        if (rangeMatch) {
            const lowerBound = parseInt(rangeMatch[1], 10);
            if (count >= lowerBound) {
                selectedText = choice.replace(`[${lowerBound},*]`, '').trim(); // Enlève la plage
                break;
            }
        }
    }

    // Si aucune règle n'a été trouvée, prendre la dernière règle comme fallback
    if (!selectedText) {
        selectedText = choices[choices.length - 1].replace(/\{.*\}/g, '').trim(); // Enlève les balises {x}
    }

    // Remplacer :count dans la chaîne sélectionnée
    return selectedText.replace(':count', count.toString());
};

export {trans, trans_params, trans_choice};
