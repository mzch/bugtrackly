import {usePage} from "@inertiajs/vue3";
import {chain, upperFirst, slice} from "lodash";
import sha256 from "crypto-js/sha256.js";

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

const generateInitials = (name) =>{
    const res = chain(name)
        .split(' ') // Divise par espaces pour obtenir un tableau de mots
        .map(part => upperFirst(part[0])) // Prend la première lettre de chaque mot et la met en majuscule
        .join('') // Combine toutes les lettres en une seule chaîne
        .value() // Retourne la valeur finale sous forme de chaîne;
        .slice(0, 3) // Coupe la chaîne pour garder les 3 premiers caractères
    return res;
}

const getGravatarUrl = (email) => {
    if(!validateEmail(email)){
        return false;
    }
    const hashedEmail = sha256( email.trim() );
    return `https://www.gravatar.com/avatar/${hashedEmail}?d=404&s=256`;
}

const gravatarExists = async (gravatarUrl) => {
    try {
        const response = await fetch(gravatarUrl, { method: 'HEAD' }); // Envoie une requête HEAD
        return response.ok; // true si l'image existe (code 200)
    } catch (error) {
        // console.error('Erreur lors de la requête Gravatar:', error);
        return false;
    }
};

const validateEmail = (str) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(str);
}
export {generatePassword,getGravatarUrl,gravatarExists, generateInitials, hasRole, hasPermission, validateEmail}
