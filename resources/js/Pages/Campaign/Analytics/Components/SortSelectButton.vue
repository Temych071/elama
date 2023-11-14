<template>
    <div @click="onClick" class="tree-table__th-item tree-table__th-item_clickable">
        <nobr>
            <slot/>
            <img v-if="isSelected"
                 :src="imgSrc"
                 :alt="imgAlt"
                 class="inline"
            >
            <img v-if="!withoutChart"
                 src="/icons/chart.svg"
                 :alt="imgAlt"
                 class="inline"
                 :class="{'opacity-30': fieldForChartProxy !== field, 'ml-2': !isSelected}"
                 @click.stop.prevent="fieldForChartProxy = fieldForChartProxy === field ? null : field"
            >
        </nobr>
    </div>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    field: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [Object, null],
        required: true,
    },

    // false - desc, true = asc
    defaultDirection: {
        type: Boolean,
        required: false,
        default: false,
    },

    fieldForChart: {
        type: [String, null],
        required: false,
        default: null,
    },
    withoutChart: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue', 'update:fieldForChart']);

const fieldForChartProxy = computed({
    get: () => props.fieldForChart,
    set: (v) => emit('update:fieldForChart', v)
});

const isSelected = computed(() => {
    return selectedValue.value?.field === props.field;
});

function onClick() {
    if (selectedValue.value?.field !== props.field) {
        selectedValue.value = {
            field: props.field,
            direction: props.defaultDirection,
        };
    } else if (selectedValue.value.direction === props.defaultDirection) {
        selectedValue.value = {
            field: props.field,
            direction: !props.defaultDirection,
        }
    } else {
        selectedValue.value = null;
    }
}

const selectedValue = computed({
    get: () => props.modelValue,
    set: (v) => emit('update:modelValue', v),
});

const imgAlt = computed(() => {
    if (selectedValue.value?.field !== props.field) {
        return '';
    } else if (selectedValue.value?.direction) {
        return 'asc';
    } else {
        return 'desc';
    }
});

const imgSrc = computed(() => {
    if (selectedValue.value?.direction) {
        return '/icons/arrow_up.svg';
    } else {
        return '/icons/arrow_down.svg';
    }
});

</script>
