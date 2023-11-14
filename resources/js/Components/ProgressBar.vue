<template>
    <div :class="`${bgColorClass}`" :style="{height}" class="overflow-hidden">
        <div :class="fillColorClass" class="h-full" :style="{width: `${percents}%`}"></div>
    </div>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    total: {
        type: Number,
        required: false,
        default: 100,
    },
    value: {
        type: Number,
        required: true,
    },
    height: {
        type: String,
        required: false,
        default: '20px',
    },
    bgColorClass: {
        type: String,
        required: false,
        default: 'bg-gray-100',
    },
    colorClass: {
        type: String,
        required: false,
        default: 'bg-primary',
    },
    colorByValue: {
        type: Boolean,
        required: false,
        default: false,
    },
    colorClasses: {
        type: Array,
        required: false,
        default: [
            {from: 0, class: 'bg-error'},
            {from: 50, class: 'bg-warning'},
            {from: 70, class: 'bg-primary'},
            {from: 90, class: 'bg-success'},
        ],
    },
});

const fillColorClass = computed(() => {
    if (props.colorByValue) {
        let lst = props.colorClasses.sort((a, b) => {
            if (a.from > b.from) {
                return -1;
            }
            return Number(a.from < b.from);
        });

        for (let i in lst) {
            if (percents.value > lst[i].from) {
                return lst[i].class;
            }
        }
    }

    return props.colorClass;
});

const percents = computed(() => {
    if (props.total === 0) {
        return 1;
    }

    return (props.value / props.total) * 100
});
</script>
