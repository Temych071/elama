<template>
    <tr v-if="isLoading" v-show="!hidden">
        <td colspan="9999" class="py-2 ml-4">Загрузка...</td>
    </tr>
    <template v-else-if="!isEmpty(items)">
        <tree-table-item v-for="item in sortedItems"
                         :key="item.index"
                         :date-range="dateRange"
                         :data="item"
                         :project="project"
                         :hidden="hidden"
                         :sort-state="sortState"
                         :comparing="comparing"
                         :comparing-date-range="comparingDateRange"
                         :comparing-data="comparingItems[item.index]"
                         :fields="fields"
        />
    </template>
    <tr v-else v-show="!hidden">
        <td colspan="9999" class="py-2 ml-4">Пусто.</td>
    </tr>
</template>

<script setup>
import {computed, nextTick, onBeforeMount, ref, watch} from 'vue';
import {isEmpty, isEmpty2, r} from "@/utils";
import TreeTableItem from "@/Pages/Campaign/Components/ProjectsList/TreeTableItem.vue";

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
    project: {
        type: Object,
        required: true,
    },
    hidden: {
        type: Boolean,
        required: false,
        default: false,
    },
    sortState: {
        type: Object,
        required: false,
        default: null,
    },
    fields: {
        type: Object,
        required: true,
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

    useExternalData: {
        type: Boolean,
        required: false,
        default: false,
    },
    itemsData: {
        type: [Array, null],
        required: false,
        default: [],
    },
    comparingItemsData: {
        type: [Array, null],
        required: false,
        default: [],
    },
});

const FIELDS_SORT_REVERSES = {
    cpl: true,
    cpc: true,
};

const emit = defineEmits(['updateSummaryMetrics', 'updateComparingSummaryMetrics']);

const items = computed({
    get: () => (props.useExternalData ? props.itemsData : loadedItems.value) ?? [],
    set: (val) => loadedItems.value = [...val],
});

const comparingItems = computed({
    get: () => _.keyBy((props.useExternalData ? props.comparingItemsData : loadedComparingItems.value) ?? [], 'index'),
    set: (val) => loadedComparingItems.value = [...val],
});

const loadedItems = ref([]);
const loadedComparingItems = ref([]);
const isLoading = ref(false);

const sortedItems = computed(() => {
    if (!items.value) {
        return [];
    }

    let res = [...items.value];

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

function reloadData() {
    if (!props.useExternalData) {
        isLoading.value = true;
        items.value = [];
        loadedComparingItems.value = [];
        loadData();
    }
}

async function loadData() {
    if (props.useExternalData) {
        return;
    }

    isLoading.value = true;

    if (isEmpty2(items.value)) {
        let res = await axios.get(route('campaign.analytics-data', {
            campaign: props.project,
            dateRange: props.dateRange,
            path: props.path,
        }));

        loadedItems.value = res?.data?.items ?? [];
    }

    if (props.comparing && isEmpty2(loadedComparingItems.value)) {
        let res = await axios.get(route('campaign.analytics-data', {
            campaign: props.project,
            dateRange: props.comparingDateRange,
            path: props.path,
        }));
        res = res?.data?.items ?? [];

        loadedComparingItems.value = _.keyBy(res, 'index');
    }

    isLoading.value = false;
}

onBeforeMount(loadData);

</script>
