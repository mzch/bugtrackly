<script setup>
import Card from "@/Components/ui/Card.vue";
import ApplicationLogoHr from "@/Components/ui/ApplicationLogoHr.vue";
import LanguageSwitcher from "@/Components/ui/LanguageSwitcher.vue";
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
            <div class="text-center mt-3">
                <LanguageSwitcher class="w-auto" id="bottom_ls"/>
            </div>
        </main>
    </div>
</template>
