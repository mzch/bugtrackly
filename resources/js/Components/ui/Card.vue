<template>
    <div class="card">
        <slot name="cardImg"/>
        <div class="card-header d-flex align-items-center justify-content-between" v-if="$slots.cardHeaderAction || props.cardTitle">
            <h3 class="mb-0 txt_16 fw-medium d-flex align-items-center" v-if="props.cardTitle || props.cardIcon">
                <component :is="cardIcon" v-if="props.cardIcon" class="size-1 me-1"/>
                {{cardTitle}}
            </h3>
            <div class="ms-auto flex-grow-1" v-if="$slots.cardHeaderAction">
                <div class="container-fluid px-0">
                    <div class="row gx-1 align-items-center justify-content-end">
                        <slot name="cardHeaderAction"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body" :class="{'p-0': removeBodyPadding}">
            <slot/>
        </div>
        <div class="card-footer text-secondary" v-if="$slots.cardFooter">
            <slot name="cardFooter"/>
        </div>

    </div>
</template>

<script setup>
    const props = defineProps({
        cardTitle:{
            type:String,
            required:false,
        },
        cardIcon:{
            type:Object,
            required:false
        },
        removeBodyPadding:{
            type:Boolean,
            default:false,
        }
    })
</script>
