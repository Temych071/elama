<template>
    <tr v-if="isLoading" v-show="!hidden">
        <td colspan="9999" class="py-2 ml-4">Загрузка...</td>
    </tr>
    <template v-else-if="!isEmpty(items)">
        <tree-table-item v-for="i of Math.min(sortedItems.length, renderCounter)"
                         :key="sortedItems[i - 1].index"
                         :date-range="dateRange"
                         :data="sortedItems[i - 1]"
                         :campaign="campaign"
                         :hidden="hidden"
                         :summary-metrics="summaryMetrics"
                         :sort-state="sortState"
                         v-model:items-for-chart="itemsForChartProxy"
                         :comparing="comparing"
                         :comparing-date-range="comparingDateRange"
                         :comparing-data="comparingItems[sortedItems[i - 1].index]"
                         :comparing-summary-metrics="comparingSummaryMetrics"
                         :first-metrics="firstMetrics"
                         :fields="fields"
                         :without-chart="withoutChart"
        />
    </template>
    <tr v-else v-show="!hidden">
        <td colspan="9999" class="py-2 ml-4">Пусто.</td>
    </tr>
</template>

<script setup>
import {computed, nextTick, onBeforeMount, ref, watch} from 'vue';
import TreeTableItem from "@/Pages/Campaign/Analytics/Components/TreeTableItem.vue";
import {analyticsCalcSummary, isEmpty, isEmpty2, ls} from "@/utils";

const props = defineProps({
    path: {
        type: Array,
        required: false,
        default: [],
    },
    dateRange: {
        type: String,
        required: true,
    },
    campaign: {
        type: Object,
        required: true,
    },
    hidden: {
        type: Boolean,
        required: false,
        default: false,
    },
    summaryMetrics: {
        type: Object,
        required: false,
        default: {},
    },
    sortState: {
        type: Object,
        required: false,
        default: null,
    },
    itemsForChart: {
        type: Array,
        required: false,
        default: [],
    },
    withoutChart: {
        type: Boolean,
        required: false,
        default: false,
    },
    fields: {
        type: Object,
        required: true,
    },
    rootMetrics: {
        type: Object,
        required: false,
        default: null,
    },
    comparingRootMetrics: {
        type: Object,
        required: false,
        default: null,
    },

    comparing: {
        type: Boolean,
        required: false,
        default: false,
    },
    comparingDateRange: {
        type: String,
        required: false,
        default: '7days',
    },
    comparingSummaryMetrics: {
        type: Object,
        required: false,
        default: {},
    },
});

const FIELDS_SORT_REVERSES = {
    cpl: true,
    cpc: true,
};

const emit = defineEmits([
    'updateSummaryMetrics',
    'updateComparingSummaryMetrics',
    'update:itemsForChart',
    'loaded',
]);

defineExpose({
    updateAllData: () => reloadData(),
});

const itemsForChartProxy = computed({
    get: () => props.itemsForChart,
    set: (v) => emit('update:itemsForChart', v)
});

const firstMetrics = computed(() => {
    if (sortedItems.value.length < 1) {
        return null;
    }

    if (isEmpty(props.sortState) || !props.sortState?.direction) {
        return sortedItems.value[0]?.metrics;
    }

    return sortedItems.value[sortedItems.value.length - 1]?.metrics;
});

const items = ref([]);
const comparingItems = ref([]);
const isLoading = ref(true);
const hideItemsWithoutExpenses = ls.use('analytics.settings.show-items-without-expenses', ls.BOOL);

const renderCounter = ref(0);
const renderIntervalHandler = ref(null);
function startRenderItems() {
    if (renderIntervalHandler.value !== null) {
        clearInterval(renderIntervalHandler);
    }

    renderCounter.value = 50;
    renderIntervalHandler.value = setInterval(() => {
        renderCounter.value += 50;
        if (renderCounter.value >= items.value.length) {
            clearInterval(renderIntervalHandler.value);
        }
    }, 100);
}

watch(() => props.hidden, () => {
    if (!props.hidden) {
        startRenderItems();
    } else {
        clearInterval(renderIntervalHandler.value);
        renderCounter.value = 0;
    }
});

watch(() => props.sortState, () => {
    if (!props.hidden) {
        startRenderItems();
    }
}, {deep: true, flush: 'pre'});

const sortedItems = computed(() => {
    if (!items.value) {
        return [];
    }

    let res = items.value;
    if (hideItemsWithoutExpenses.value) {
        res = res.filter((item) => {
            let sum = 0;
            for (let val of Object.values(item.metrics)) {
                sum += val;
            }
            return sum > 0;
        });
    }

    if (isEmpty(props.sortState)) {
        return res;
    }

    let dir = props.sortState.direction;
    if (FIELDS_SORT_REVERSES[props.sortState.field] ?? false) {
        dir = !dir;
    }

    return res.sort(
        dir
            ? (a, b) => a?.metrics[props.sortState.field] - b?.metrics[props.sortState.field]
            : (a, b) => b?.metrics[props.sortState.field] - a?.metrics[props.sortState.field]
    );
});

watch(() => props.dateRange, reloadData, {deep: true});
watch(() => props.comparing, loadData, {deep: true});
watch(() => props.comparingDateRange, reloadData, {deep: true});

async function reloadData() {
    isLoading.value = true;
    items.value = [];
    comparingItems.value = [];
    await loadData();
}

async function loadData() {
    isLoading.value = true;

    if (isEmpty2(items.value)) {
        let res = await axios.get(route('campaign.analytics-data', {
            campaign: props.campaign,
            dateRange: props.dateRange,
            path: props.path,
        }));

        items.value = res?.data?.items ?? [];
    }

    if (props.comparing && isEmpty2(comparingItems.value)) {
        let res = await axios.get(route('campaign.analytics-data', {
            campaign: props.campaign,
            dateRange: props.comparingDateRange,
            path: props.path,
        }));
        res = res?.data?.items ?? [];

        comparingItems.value = _.keyBy(res, 'index');
    }

    isLoading.value = false;
    startRenderItems();

    await nextTick();

    emit('updateSummaryMetrics', analyticsCalcSummary(items.value));
    if (props.comparing) {
        emit('updateComparingSummaryMetrics', analyticsCalcSummary(comparingItems.value));
    }
}

onBeforeMount(async () => {
    await loadData();

    await nextTick();
    emit('loaded', items.value);
});

</script>
