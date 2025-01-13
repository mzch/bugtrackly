<template>
    <transition
        name="expand"
        :css="false"
        @before-enter="onBeforeEnter"
        @enter="onEnter"
        @after-enter="onAfterEnter"
        @before-leave="onBeforeLeave"
        @leave="onLeave"
        @after-leave="onAfterLeave"
    >
        <slot/>
    </transition>
</template>
<script setup>
import gsap from "gsap";
import {onMounted, ref} from "vue";

const props = defineProps({
    duration:{
        type:Number,
        default:.2,
        required:false,
    },
    noDurationOnMounted:{
        type:Boolean,
        default:false,
        required:false,
    }
})
const defaultDuration = props.duration;
const duration = ref(defaultDuration);
const ease = "power1.out";
const entered = ref(false);
const onBeforeEnter = (el) => gsap.set(el, {height:0, overflow:'hidden'});
const onBeforeLeave = (el) => {
    gsap.set(el, {overflow: 'hidden'})
};
const onAfterLeave = (el) => {
}
const onAfterEnter = (el) => gsap.set(el, {overflow:'visible'});

const onEnter = (el, done) => gsap.to(el, {duration:duration.value, height:"auto", ease, onComplete: () => {
        duration.value = defaultDuration;
        entered.value = true;
        done();
    }})
const onLeave = (el, done) => gsap.to(el, {duration:duration.value, height:0, ease, onComplete: () => done()})

onMounted(() => {
    if(props.noDurationOnMounted && !entered){
        duration.value = 0;
    }
})

</script>
