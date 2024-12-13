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

export  {hasRole, hasPermission};
