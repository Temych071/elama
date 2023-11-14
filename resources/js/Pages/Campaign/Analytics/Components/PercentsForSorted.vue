<template>
    <span v-if="metric === 0 && comparingMetric === 0">-</span>
    <span v-else>
        <div class="flex-shrink-0 z-10" v-if="!isSorted">{{ displayMetric }}</div>
        <div v-else
              class="tree-table__sorted-bar"
              :style="{'background-color': '#F6F8FA'}"
        >
            <span class="tree-table__sorted-bar_filling"
                  :style="{width: `${barWidthPercents}%`, 'background-color': bgColor}"
            ></span>
            <span class="tree-table__sorted-bar__percents">{{ percents }} %</span>
            <span class=""></span>
            <span :style="{color: textColor}" class="tree-table__sorted-bar__value">{{ displayMetric }}</span>
        </div>

        <template v-if="comparing && issetComparingMetric">
            <div v-if="percentsComparing !== null"
                 :style="{color: textColorComparing}"
                 class="tree-table__compare-percents"
                 :title="`Было: ${displayComparingMetric}`"
            >
                <svg v-if="iconRotateDeg !== null"
                     width="7"
                     height="6"
                     class="mr-1"
                     :style="`color: ${textColorComparing};`"
                >
                    <use
                        xlink:href="#tree-table__compare-arrow" x="0" y="0"
                        :transform="`rotate(${iconRotateDeg} 3.5 3)`"
                    />
                </svg>
                <span>{{ percentsComparing }} %</span>
            </div>
            <div v-else class="text-gray-400">
                -
            </div>
        </template>
    </span>
</template>

<script setup>
import {computed} from "vue";
import {r, n, clamp, diffPercents, isEmpty2} from '@/utils';

const props = defineProps({
    field: {
        type: String,
        required: true,
    },
    sortState: {
        type: Object,
        required: false,
        default: null,
    },
    metrics: {
        type: Object,
        required: false,
        default: {},
    },
    summary: {
        type: Object,
        required: false,
        default: {},
    },
    first: {
        type: Object,
        required: false,
        default: null,
    },

    comparing: {
        type: Boolean,
        required: false,
        default: false,
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

const isSorted = computed(() => {
    return props.sortState?.field === props.field;
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

const summaryMetrics = computed(() => {
    return n(props.summary[props.field] ?? 0);
});

const firstMetrics = computed(() => {
    return n(props.first[props.field] ?? 0);
});

const percents = computed(() => {
    if (!summaryMetrics.value) {
        return 0;
    }
    return r((metric.value / summaryMetrics.value) * 100, 2);
});

const barWidthPercents = computed(() => clamp(r((metric.value / firstMetrics.value) * 100, 0), 10, 100));

const bgColor = computed(() => {
    switch (colorName.value) {
        case 'green':
            return '#D2F3D2';
        case 'yellow':
            return '#FFF9DB';
        case 'red':
            return '#FF8787';
    }
});

const colorName = computed(() => {
    if (barWidthPercents.value < 15) {
        return 'red';
    } else if (barWidthPercents.value < 45) {
        return 'yellow';
    } else {
        return 'green';
    }
});

const textColor = computed(() => {
    switch (colorName.value) {
        case 'green':
            return '#55B955';
        case 'yellow':
            return '#FFD43B';
        case 'red':
            return '#FF8787';
    }
});

const percentsComparing = computed(() => {
    if (!comparingMetric.value) {
        return null;
    }
    return diffPercents(comparingMetric.value, metric.value, false);
});

const colorNameComparing = computed(() => {
    if (metric.value < comparingMetric.value) {
        return props.fields[props.field].reverse ? 'green' : 'red';
    } else if (metric.value === comparingMetric.value) {
        return 'yellow';
    } else {
        return props.fields[props.field].reverse ? 'red' : 'green';
    }
});

const textColorComparing = computed(() => {
    switch (colorNameComparing.value) {
        case 'green':
            return props.fields[props.field].reverseColor ? '#E96565' : '#76D276';
        case 'yellow':
            return '#f6d353';
        case 'red':
            return props.fields[props.field].reverseColor ? '#76D276' : '#E96565';
    }
});

const iconRotateDeg = computed(() => {
    switch (colorNameComparing.value) {
        case 'green':
            return props.fields[props.field].reverseArrow ? 0 : 180;
        case 'yellow':
            return null;
        case 'red':
            return props.fields[props.field].reverseArrow ? 180 : 0;
    }
});

</script>
