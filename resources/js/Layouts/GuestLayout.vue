<script setup>
import Card from "@/Components/ui/Card.vue";
import ApplicationLogoHr from "@/Components/ui/ApplicationLogoHr.vue";
</script>

<template>
    <div id="guest-wrapper" class="container-fluid">
        <main>
            <div class="logo-wrapper">
                <ApplicationLogoHr class="img-fluid" />
            </div>
            <Card>
                <slot />
            </Card>
        </main>
    </div>
</template>
