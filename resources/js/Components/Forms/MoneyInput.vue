<template>
    <input
        :value="modelValue / 1000"
        @input="emit('update:modelValue', $event.target.value * 1000)"
        v-bind="props"
    />
</template>

<script setup>
const props = defineProps({
    modelValue: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);
</script>
