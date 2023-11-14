<template>
    <div class="accordion">
        <values-accordion-item
            v-for="(_, index) in months"
            :key="_.month ?? _"
            v-model.number="months[index]"
            :buttons="buttons[String(index)] ?? null"
        />
    </div>
</template>

<script setup>
import ValuesAccordionItem from "@/Pages/Campaign/PlanFact/Components/ValuesAccordionItem.vue";
import {computed} from "vue";

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
    buttons: {
        type: Object,
        required: false,
        default: {},
    },
});

const emit = defineEmits(['update:modelValue']);

const months = computed({
    get: () => props.modelValue,
    set: (v) => emit('update:modelValue', v),
});
</script>
