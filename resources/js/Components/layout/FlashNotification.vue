<template>
    <notifications classes="my-notification"
                   animation-type="velocity"
                   :dangerously-set-inner-html="true"
                   position="bottom center"/>
</template>

<script setup>
import {computed, nextTick, onMounted, watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import {notify} from "@kyvg/vue3-notification";
    const flash = computed(() => usePage().props.flash)


    watch(() => flash.value, newFlash => {
        if(newFlash){
            const messageTypes = ['success', 'error', 'info', 'warning'];
            messageTypes.forEach(type => {
                const message = newFlash[type];
                if (message) {
                    const { title, text } = message;
                    nextTick(() => {
                        notify({
                            title,
                            text,
                            type,
                        });
                    });
                }
            });
        }
    }, { immediate:true})
</script>
