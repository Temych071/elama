<template>
    <private-layout :loyalties="loyalties"/>
</template>

<script setup>
import PrivateLayout from "@/Pages/Loyalty/Layouts/PrivateLayout.vue";

const props = defineProps({
    loyalties: {
        type: Array,
        required: true,
    },
});

</script>
