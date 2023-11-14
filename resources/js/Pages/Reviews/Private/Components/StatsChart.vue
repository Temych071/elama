<template>
    <div ref="chartContainerEl"></div>
</template>

<script setup>
import {computed, nextTick, onMounted, ref, watch} from "vue";
import {dateRangeFormatWithWeekDay} from "@/dateRange.js";
import Chart from "chart.js/auto";

const props = defineProps({
    data: {
        type: Object,
        required: true,
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
    return {
        datasets: [{
            label: 'Показатель',
            data: Object.values(props.data),
            backgroundColor: '#8191E311',
            pointBackgroundColor: '#8191E3',
            borderColor: '#8191E3',
            borderWidth: 2,
            fill: true,
            tension: 0.3,
            pointRadius: 1,
        }],
        labels: Object.keys(props.data).map((item) => dateRangeFormatWithWeekDay(item, true)),
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
