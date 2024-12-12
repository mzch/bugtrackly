<template>
    <teleport to="body">
        <div v-if="htmlMarkupVisible" class="modal fade" :id="id" tabindex="-1" :aria-labelledby="labelledby" ref="modal" :style="{'display': show || htmlMarkupVisible ? 'block' : 'none'}">
            <div ref="modalDialog" class="modal-dialog modal-dialog-centered" :class="extraClass">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" :id="labelledby">
                            <slot name="title">
                                Default title
                            </slot>
                        </h4>
                        <button type="button"  @click="closeModal" class="btn-close"  aria-label="Close"></button>
                    </div>
                    <slot name="content"></slot>
                </div>
            </div>
        </div>
        <div v-if="htmlMarkupVisible" class="modal-backdrop fade" ref="backdrop"></div>
    </teleport>
</template>

<script setup>
import {computed, watch, ref, onUnmounted} from "vue";
const emit = defineEmits(['open', 'opened', 'close', 'closed']);

const props = defineProps(
    {
        id: {
            type: String,
            required: true
        },
        extraClass: {
            type: [String,Boolean],
            default:false
        },
        show:{
            type: Boolean,
            default:false
        }
    }
);
const labelledby = computed(() => props.id + "Label");

const backdrop = ref(null);
const modal = ref(null);
const modalDialog = ref(null);

const htmlMarkupVisible = ref(props.show);

let timer = null;

const closeModal = () => emit('close');

watch(() => props.show, (newValue, oldValue) => {
    if(newValue){
        htmlMarkupVisible.value = true;
        document.body.classList.add("modal-open");
        timer = setTimeout( () => {
            backdrop.value.classList.add("show");
            modal.value.classList.add("show");
            document.body.addEventListener("click", closeModalByClick);
            document.addEventListener('keydown', keyDownHandler);
        }, 20)

    }else{
        document.body.classList.remove("modal-open");
        backdrop.value.classList.remove("show");
        modal.value.classList.remove("show");
        document.body.removeEventListener("click", closeModalByClick);
        document.removeEventListener('keydown', keyDownHandler);
        timer = setTimeout( () => {
            htmlMarkupVisible.value = false
        }, 300)
    }
});
const closeModalByClick = (e) => {
    if (! modalDialog.value.contains(e.target)) {
        closeModal()
    }
}
const keyDownHandler = (e) => {
    if(e.key === "Escape"){
        closeModal()
    }
}
onUnmounted(()=>{
    document.body.removeEventListener("click", closeModalByClick);
    document.removeEventListener('keydown', keyDownHandler);
    clearTimeout(timer);
    timer = null;
})

</script>
