import {usePage} from "@inertiajs/vue3";

/**
 * Check current user role
 * @param role_to_check
 * @returns {{return: boolean}}
 */
const hasRole = (role_to_check) => usePage().props.auth.user.role.internal_name === role_to_check;

/**
 * Check current user permission
 * @type {{return: boolean}}
 */
const hasPermission = permission =>  usePage().props.auth.user.role.permissions.includes(permission)



const generatePassword = (length = 12) => {
    const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"\'!@#$-_%^&*()'; // Ensemble de caractères autorisés
    let password = '';
    // Génère des valeurs aléatoires sécurisées avec l'API Web Crypto
    const randomValues = new Uint32Array(length);
    window.crypto.getRandomValues(randomValues);
    // Boucle pour générer un mot de passe avec les caractères définis dans charset
    for (let i = 0; i < length; i++) {
        password += charset[randomValues[i] % charset.length];
    }
    return password;
}
export {generatePassword, hasRole, hasPermission}
