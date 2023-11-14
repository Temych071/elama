<template>
    <div class="analytics-chart">
        <div v-if="isEmpty(field)" class="text-center p-4" style="height: 500px;">
            Для отображения графика надо выбрать колонку.
        </div>
        <div v-else-if="isLoading" class="flex flex-row justify-center p-4 items-center" style="height: 400px;">
            <loading-spinner dark/>
        </div>
        <div v-show="!isEmpty(field) && !isLoading" ref="chartContainerEl"></div>
    </div>
</template>

<script setup>
import {isEmpty, isEmpty2} from "@/utils";
import {dateRangeFormat, dateRangeFormatWithWeekDay} from '@/dateRange';
import LoadingSpinner from "@/Components/LoadingSpinner.vue";
import {computed, nextTick, onBeforeMount, onMounted, ref, watch} from "vue";
import {Chart} from "chart.js/auto/auto";

const props = defineProps({
    dateRange: {
        type: [String, Array, Object],
        required: true,
    },
    campaign: {
        type: Object,
        required: true,
    },

    field: {
        type: [String, null],
        required: false,
        default: null,
    },
    paths: {
        type: Array,
        required: false,
        default: [],
    },

    comparingDateRange: {
        type: [String, Array, Object],
        required: false,
        default: '7days',
    },
    comparing: {
        type: Boolean,
        required: false,
        default: false,
    },

    groupType: {
        type: String,
        required: false,
        default: 'days-1',
    },
});

defineExpose({
    updateAllData: () => {
        pathsDataForCompare.value = {};
        pathsData.value = {};
        loadData().then(updateChart);
    },
});

const isLoading = ref(false);
const chartContainerEl = ref(null);
const chart = ref();
const pathsData = ref({});
const pathsDataForCompare = ref({});

onBeforeMount(() => {
    loadData().then(updateChart);
});

onMounted(() => {
    updateChart();
});

const pathsForDisplay = computed(() => isEmpty(props.paths) ? [{
    path: `chart:${props.groupType}`,
    name: 'Итого и среднее',
}] : props.paths);

async function loadData() {
    for (let i in pathsForDisplay.value) {
        let path = pathsForDisplay.value[i].path;

        if (isEmpty(pathsData.value[path])) {
            isLoading.value = true;

            pathsData.value[path] = (await axios.get(route('campaign.analytics-data', {
                campaign: props.campaign,
                path: path ? path + `,chart:${props.groupType}` : `chart:${props.groupType}`,
                dateRange: props.dateRange,
            })))?.data?.items;
        }

        if (props.comparing && isEmpty(pathsDataForCompare.value[path])) {
            pathsDataForCompare.value[path] = (await axios.get(route('campaign.analytics-data', {
                campaign: props.campaign,
                path: path + `,chart:${props.groupType}`,
                dateRange: props.comparingDateRange,
            })))?.data?.items;
        }

        await nextTick();
        isLoading.value = false;
    }
}

function updateChart() {
    if (chartContainerEl.value) {
        chartContainerEl.value.innerHTML = '';
        nextTick().then(initChart);
    }
}

watch(() => props.dateRange, () => {
    pathsData.value = {};
    loadData().then(updateChart);
}, {deep: true});

watch(() => props.comparingDateRange, () => {
    pathsDataForCompare.value = {};
    loadData().then(updateChart);
}, {deep: true});

watch(() => props.groupType, () => {
    pathsDataForCompare.value = {};
    pathsData.value = {};
    loadData().then(updateChart);
}, {deep: true});

watch(() => props.comparing, () => {
    loadData().then(updateChart);
}, {deep: true});

watch(() => pathsForDisplay.value, (to) => {
    for (let i in to) {
        if (
            isEmpty(pathsData.value[to[i].path])
            || (
                props.comparing
                && isEmpty(pathsDataForCompare.value[to[i].path])
            )
        ) {
            loadData().then(updateChart);
            return;
        }
    }
    updateChart();
}, {deep: true});

watch(() => props.field, () => {
    updateChart();
}, {deep: true});

function formatDateRangeForLabel(dateRange, type) {
    switch (type) {
        case 'tooltip':
            return dateRangeFormatWithWeekDay(dateRange, true);
        case 'axis':
            if (isEmpty2(dateRange)) {
                return '';
            }
            return dateRangeFormat(dateRange, true, false, {withoutYear: true})
                .replaceAll('.', '');
        default:
            return dateRangeFormatWithWeekDay(dateRange, true)
                .replaceAll('.', '');
    }
}

function computeChartData() {
    let res = {
        datasets: [],
        labels: [],
        comparedLabels: [],
    };

    const lineColors = [
        '88B5E7',
        '9A73C2',
        'A8EA91',
        'EDA869',
        '8191E3',
        'EC839A',
    ];

    const makeDataset = (data, name, color, compared = false) => {
        let dataset = {
            compared,

            label: name + (compared ? ' (ср.)' : ''),
            data: {},

            borderColor: `#${color}`,
            pointBackgroundColor: `#${color}`,
            backgroundColor: `#${color}${compared ? '11' : '21'}`,
            borderDash: compared ? [10, 5] : [],

            borderWidth: 2,
            fill: true,
            tension: 0.3,
            pointRadius: 1,

            xAxisID: compared ? 'x1' : 'x',
        };

        for (let j in data) {
            let item = data[j];

            if (compared) {
                if (!res.comparedLabels.includes(item.name)) {
                    res.comparedLabels.push(item.name);
                }
            } else {
                if (!res.labels.includes(item.name)) {
                    res.labels.push(item.name);
                }
            }

            dataset.data[item.name] = item.metrics[props.field];
            // dataset.data[formatDateRangeForLabel(item.name)] = item.metrics[props.field];
        }

        return dataset;
    };

    for (let i in pathsForDisplay.value) {
        let path = pathsForDisplay.value[i].path;
        let name = pathsForDisplay.value[i].name;

        if (!isEmpty(pathsData.value[path])) {
            res.datasets.push(makeDataset(pathsData.value[path], name, lineColors[i], false));
        }

        if (props.comparing && !isEmpty(pathsDataForCompare.value[path])) {
            res.datasets.push(makeDataset(pathsDataForCompare.value[path], name, lineColors[i], true));
        }
    }

    const labelsSortComparator = (a, b) => (new Date(a)).getTime() - (new Date(b)).getTime();

    res.labels = res.labels
        .sort(labelsSortComparator);
        // .map((item) => formatDateRangeForLabel(item));

    res.comparedLabels = res.comparedLabels
        .sort(labelsSortComparator);
        // .map((item) => formatDateRangeForLabel(item));

    let count = Math.max(res.labels.length, res.comparedLabels.length);
    let newLabels = [];
    for (let i = 0; i < count; i++) {
        newLabels.push(`${res.labels[i] ?? ''},${res.comparedLabels[i] ?? ''}`);
    }
    res.labels = newLabels;

    for (let i in res.datasets) {
        let sorted = {};

        for (let j in res.labels) {
            let label = res.labels[j].split(',', 2);
            let val = null;
            if (res.datasets[i].compared) {
                val = res.datasets[i].data[label[1]] ?? null;
            } else {
                val = res.datasets[i].data[label[0]] ?? null;
            }
            if (val !== null) {
                sorted[label] = val;
            }
        }
        res.datasets[i].data = sorted;
    }

    return res;
}

function initChart() {
    if (!chartContainerEl.value) {
        return;
    }

    let el = document.createElement('canvas');
    el.height = 400;
    chartContainerEl.value.append(el);
    chart.value = new Chart(el.getContext('2d'), {
        type: 'line',
        data: computeChartData(),

        options: {
            maintainAspectRatio: false,
            borderWidth: 1,

            plugins: {
                tooltip: {
                    callbacks: {
                        title: (tooltipItems) => {
                            return tooltipItems[0]?.label
                                .split(',')
                                .map((i) => i ? formatDateRangeForLabel(i, 'tooltip') : null)
                                .filter((i) => i !== null)
                                .join(' - ');
                        },
                    },
                },
            },

            interaction: {
                intersect: false,
                mode: 'index',
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
                        display: true,
                        callback: function (label) {
                            return formatDateRangeForLabel((this.getLabelForValue(label)?.split(',') ?? [])[0] ?? null, 'axis');
                        }
                    }
                },
                x1: {
                    display: props.comparing,
                    position: 'top',
                    grid: {
                        color: '#F9F9F9',
                    },
                    ticks: {
                        display: true,
                        callback: function (label) {
                            return formatDateRangeForLabel((this.getLabelForValue(label)?.split(',') ?? [])[1] ?? null, 'axis');
                        }
                    }
                },
            },
        },
    });
}

</script>
