<template>
    <span @click.stop="value = !value" class="inline cursor-pointer">
        <img v-if="value" src="/icons/slider-deploy-up.svg" alt="+" class="inline">
        <img v-else src="/icons/slider-deploy-down.svg" alt="-" class="inline">
    </span>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    isDeployed: Boolean,
});

const emit = defineEmits(['update:isDeployed']);

const value = computed({
    get: () => props.isDeployed,
    set: (v) => emit('update:isDeployed', v),
});
</script>
