<template>
    <div ref="chartContainerEl"></div>
</template>

<script setup>
import {computed, nextTick, onMounted, ref, watch} from "vue";
import {dateRangeFormat} from "@/dateRange.js";
import Chart from "chart.js/auto";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    colors: {
        type: Array,
        required: false,
        default: [
            '#88B5E7',
            '#9A73C2',
            '#A8EA91',
            '#EDA869',
            '#8191E3',
            '#EC839A',
        ],
    },
    min: {
        type: Number,
        required: false,
        default: null,
    },
    max: {
        type: Number,
        required: false,
        default: null,
    },
    step: {
        type: Number,
        required: false,
        default: null,
    },
});

const chartContainerEl = ref(null);
const chart = ref();

onMounted(() => {
    updateChart();
});

function updateChart() {
    chartContainerEl.value.innerHTML = '';
    nextTick().then(initChart);
}

watch(() => props.data, () => {
    updateChart();
}, {deep: true});

const chartData = computed(() => {
    let datasets = [];
    let counter = 0;
    let labels = null;

    for (let lineName in props.data) {
        const color = props.colors[counter % props.colors.length];
        datasets.push({
            label: lineName,
            data: Object.values(props.data[lineName]),
            backgroundColor: `${color}11`,
            pointBackgroundColor: color,
            borderColor: color,
            borderWidth: 2,
            fill: true,
            tension: 0.3,
            pointRadius: 1,
        });
        ++counter;

        if (labels === null) {
            labels = Object.keys(props.data[lineName]);
        }
    }

    return {
        datasets: datasets,
        labels: labels.map((item) => dateRangeFormat(item, true)),
    };
})

function initChart() {
    let el = document.createElement('canvas');
    el.height = 200;
    el.width = 300;
    chartContainerEl.value.append(el);

    chart.value = new Chart(el.getContext('2d'), {
        type: 'line',
        data: chartData.value,

        options: {
            maintainAspectRatio: false,
            borderWidth: 1,

            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    display: true,
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        color: '#F9F9F9',
                    },
                    suggestedMin: props.min,
                    suggestedMax: props.max,
                    ticks: {
                        stepSize: props.step,
                    },
                },
                x: {
                    display: true,
                    position: 'bottom',
                    grid: {
                        color: '#F9F9F9',
                    },
                    ticks: {
                        display: false,
                    },
                },
            },
        },
    });
}
</script>
