<template>
    <span v-if="metric === 0 && comparingMetric === 0">-</span>
    <span v-else>
        <div class="flex-shrink-0 z-10">{{ displayMetric }}</div>
        <template v-if="comparing && issetComparingMetric ">
            <div v-if="percents !== null"
                 :style="{color: textColor}"
                 class="flex-shrink-0 z-10 text-xs flex flex-row flex-nowrap justify-center items-center"
                 :title="`Было: ${displayComparingMetric}`"
            >
                <svg v-if="iconRotateDeg !== null"
                     width="7"
                     height="6"
                     viewBox="0 0 7 6"
                     class="mr-1"
                >
                    <path
                        d="M3.22816 5.96248C3.29021 5.93867 3.34691 5.90297 3.39498 5.85743L5.92262 3.35639C5.96975 3.30976 6.00714 3.25439 6.03265 3.19345C6.05816 3.13252 6.07129 3.0672 6.07129 3.00125C6.07129 2.86804 6.01781 2.74029 5.92262 2.6461C5.87548 2.59946 5.81953 2.56246 5.75794 2.53722C5.69636 2.51198 5.63035 2.49899 5.56369 2.49899C5.42907 2.49899 5.29996 2.55191 5.20477 2.6461L3.54159 4.29679L3.54159 0.500208C3.54159 0.367544 3.48833 0.240315 3.39352 0.146508C3.29872 0.0527004 3.17013 0 3.03606 0C2.90199 0 2.7734 0.0527004 2.6786 0.146508C2.58379 0.240315 2.53053 0.367544 2.53053 0.500208L2.53053 4.29679L0.867349 2.6461C0.820354 2.59922 0.764442 2.562 0.702839 2.53661C0.641236 2.51121 0.575161 2.49814 0.508425 2.49814C0.44169 2.49814 0.375615 2.51121 0.314011 2.53661C0.252408 2.562 0.196497 2.59922 0.149502 2.6461C0.102119 2.6926 0.0645103 2.74792 0.0388451 2.80888C0.0131803 2.86983 -3.29018e-05 2.93521 -3.29018e-05 3.00125C-3.29018e-05 3.06728 0.0131803 3.13266 0.0388451 3.19362C0.0645103 3.25457 0.102119 3.30989 0.149502 3.35639L2.67714 5.85743C2.72521 5.90297 2.78191 5.93867 2.84396 5.96248C2.96704 6.01251 3.10508 6.01251 3.22816 5.96248Z"
                        :fill="textColor"
                        :transform="`rotate(${iconRotateDeg} 3.5 3)`"
                    />
                </svg>
                <span>{{ percents }} %</span>
            </div>
            <div v-else class="text-gray-400">
                -
            </div>
        </template>
    </span>
</template>

<script setup>
import {computed} from "vue";
import {diffPercents, isEmpty2} from '@/utils';

const props = defineProps({
    field: {
        type: String,
        required: true,
    },
    comparing: {
        type: Boolean,
        required: false,
        default: false,
    },
    metrics: {
        type: Object,
        required: false,
        default: {},
    },
    comparingMetrics: {
        type: Object,
        required: false,
        default: {},
    },

    fields: {
        type: Object,
        required: false,
        default: {},
    },
});

const metric = computed(() => {
    return props.metrics[props.field] ?? 0;
});

const comparingMetric = computed(() => {
    return props.comparingMetrics[props.field] ?? 0;
});

const issetComparingMetric = computed(() => {
    return props.comparingMetrics[props.field] !== undefined;
});

const displayMetric = computed(() => {
    return metricFormat(metric.value);
});

const displayComparingMetric = computed(() => {
    return metricFormat(comparingMetric.value);
});

function metricFormat(value) {
    if (isEmpty2(value)) {
        return '-';
    }

    if (isEmpty2(props.fields) || isEmpty2(props.fields[props.field])) {
        return value;
    }

    if (!isEmpty2(props.fields[props.field].callback)) {
        return props.fields[props.field].callback(value);
    }

    return value;
}

const percents = computed(() => {
    if (!comparingMetric.value) {
        return null;
    }
    return diffPercents(comparingMetric.value, metric.value, false);
});

const colorName = computed(() => {
    if (metric.value < comparingMetric.value) {
        return 'red';
    } else if (metric.value === comparingMetric.value) {
        return 'yellow';
    } else {
        return 'green';
    }
});

const iconRotateDeg = computed(() => {
    switch (colorName.value) {
        case 'green':
            return 180;
        case 'yellow':
            return null;
        case 'red':
            return 0;
    }
});

const textColor = computed(() => {
    let bool;
    switch (colorName.value) {
        case 'green':
            bool = true;
            break;
        case 'yellow':
            return '#f6d353';
        case 'red':
            bool = false;
            break;
    }

    if (['cpl', 'cpc'].includes(props.field)) {
        bool = !bool;
    }

    return bool ? '#76D276' : '#E96565';
});

</script>
