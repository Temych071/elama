<template>
    <img v-if="status" src="/icons/checks-status-ok.svg" alt="+">
    <img v-else src="/icons/checks-status-failed.svg" alt="-">
</template>

<script setup>
const props = defineProps({
    status: {
        type: Boolean,
        required: true,
    },
});
</script>
