<template>
    <img alt="Daily Grow" src="/images/logo.svg" v-if="!small" >
    <img alt="Daily Grow" src="/images/logo_collapsed.svg" v-else>
</template>

<script setup>
const props = defineProps({
    small: {
        type: Boolean,
        required: false,
        default: false,
    },
});
</script>
