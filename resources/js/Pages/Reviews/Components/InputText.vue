<template>
    <input
        v-bind="props"
        @input="emit('update:modelValue', $event.target.value)"
        :value="displayValue"
        :type="type"
    >
</template>

<script setup>
import {computed, onMounted, watch} from "vue";

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    default: {
        type: String,
        required: false,
        default: '',
    },
    type: {
        type: String,
        required: false,
        default: 'text',
    },
});

const emit = defineEmits(['update:modelValue']);
const displayValue = computed(() => props.modelValue ?? props.default);

onMounted(handleDefaultValue);
watch(() => props.modelValue, handleDefaultValue);

// Или лучше не выбрасывать новое значение,
// а просто через computed в :value подставлять default или modelValue

function handleDefaultValue() {
    if (props.modelValue === undefined || props.modelValue === null) {
        emit('update:modelValue', displayValue.value);
    }
}
</script>
