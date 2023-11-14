<template>
    <textarea
        v-bind="{...$attrs, ...$props}"
        @input="onInput"
        :value="displayValue"
        :class="{'overflow-y-hidden resize-none': autosize}"
    />
    <input-error :message="error"/>
</template>

<script setup>
import {computed, defineComponent, onMounted, watch} from "vue";
import InputError from "@/Components/InputError.vue";

defineComponent({
    inheritAttrs: false,
});

const props = defineProps({
    modelValue: {
        type: String,
        required: false,
        default: '',
    },
    default: {
        type: String,
        required: false,
        default: '',
    },
    error: {
        type: String,
        required: false,
        default: null,
    },
    autosize: {
        type: Boolean,
        require: false,
        default: false,
    },
    minSize: {
        type: Number,
        required: false,
        default: null,
    },
});

const emit = defineEmits(['update:modelValue']);
const displayValue = computed(() => props.modelValue ?? props.default);

onMounted(handleDefaultValue);
watch(() => props.modelValue, handleDefaultValue);

function handleDefaultValue() {
    if (props.modelValue === undefined || props.modelValue === null) {
        emit('update:modelValue', displayValue.value);
    }
}

function onInput({target}) {
    emit('update:modelValue', target.value);

    if (props.autosize) {
        target.style.height = 0;

        let h = target.scrollHeight + 2;
        if (props.minSize !== null) {
            h = Math.max(h, props.minSize);
        }

        target.style.height = h + "px";
    }
}
</script>
