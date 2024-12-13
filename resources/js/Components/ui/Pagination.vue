<script setup>
import {Link} from "@inertiajs/vue3";
import {computed} from "vue";
import {formatNumber} from "@/Helpers/number.js";


const emit = defineEmits(['paginate']);
const props = defineProps({
    items: {
        type: Object,
        required: true
    }
});
const links = computed(() => props.items.links)
const showingPagination = computed(() => props.items.total > props.items.per_page)

const removePageOneParam = (url) => {
    // Utiliser une expression régulière pour supprimer '?page=1' ou '&page=1'
    return url
        .replace(/([?&])page=1(&|$)/, (match, p1, p2) => p1 === '?' ? p1 : p2 ? '?' : '')
        .replace(/\?$/, ''); // Supprimer le ? final si c'est le dernier caractère
}

</script>

<template>
    <div class="d-flex align-items-center justify-content-between footer-datatable footer-datatable-sm">
        <div class="text-secondary">
            Affichage de {{$page.props.users.from}} à {{$page.props.users.to}} sur {{$page.props.users.total}} utilisateurs
        </div>
        <nav v-if="showingPagination">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item" :class="{'disabled' : !link.url, 'active': link.active}"
                    v-for="(link, index) in links" :key="index">
                    <Link @click="emit('paginate')" :href="link.url ? removePageOneParam(link.url) : ''" preserve-scroll preserve-state
                          class="page-link" tabindex="0"><span v-html="formatNumber(link.label)"/></Link>
                </li>
            </ul>
        </nav>
    </div>

</template>



