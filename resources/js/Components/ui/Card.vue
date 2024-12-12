<template>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between" v-if="$slots.cardHeaderAction || props.cardTitle">
            <h3 class="mb-0 txt_16 fw-medium" v-if="props.cardTitle">{{cardTitle}}</h3>
            <div class="ms-auto" v-if="$slots.cardHeaderAction">
                <slot name="cardHeaderAction"/>
            </div>
        </div>
        <div class="card-body">
            <slot/>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        cardTitle:{
            type:String,
            required:false,
        }
    })
</script>
