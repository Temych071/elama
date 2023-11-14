<template>
    <div class="relative">
        <loading-block class="bg-white bg-opacity-50"
                       spinner-dark
                       v-if="inLoading"
        />

        <!--Оптимизация:)-->
        <svg class="hidden">
            <defs>
                <g id="tree-table__compare-arrow">
                    <path
                        d="M3.22816 5.96248C3.29021 5.93867 3.34691 5.90297 3.39498 5.85743L5.92262 3.35639C5.96975 3.30976 6.00714 3.25439 6.03265 3.19345C6.05816 3.13252 6.07129 3.0672 6.07129 3.00125C6.07129 2.86804 6.01781 2.74029 5.92262 2.6461C5.87548 2.59946 5.81953 2.56246 5.75794 2.53722C5.69636 2.51198 5.63035 2.49899 5.56369 2.49899C5.42907 2.49899 5.29996 2.55191 5.20477 2.6461L3.54159 4.29679L3.54159 0.500208C3.54159 0.367544 3.48833 0.240315 3.39352 0.146508C3.29872 0.0527004 3.17013 0 3.03606 0C2.90199 0 2.7734 0.0527004 2.6786 0.146508C2.58379 0.240315 2.53053 0.367544 2.53053 0.500208L2.53053 4.29679L0.867349 2.6461C0.820354 2.59922 0.764442 2.562 0.702839 2.53661C0.641236 2.51121 0.575161 2.49814 0.508425 2.49814C0.44169 2.49814 0.375615 2.51121 0.314011 2.53661C0.252408 2.562 0.196497 2.59922 0.149502 2.6461C0.102119 2.6926 0.0645103 2.74792 0.0388451 2.80888C0.0131803 2.86983 -3.29018e-05 2.93521 -3.29018e-05 3.00125C-3.29018e-05 3.06728 0.0131803 3.13266 0.0388451 3.19362C0.0645103 3.25457 0.102119 3.30989 0.149502 3.35639L2.67714 5.85743C2.72521 5.90297 2.78191 5.93867 2.84396 5.96248C2.96704 6.01251 3.10508 6.01251 3.22816 5.96248Z"
                        fill="currentColor"
                        transform="`rotate(0 3.5 3)`"
                    />
                </g>
            </defs>
        </svg>

        <table class="tree-table">
            <thead>
            <tr>
                <th class="tree-table__th-item tree-table__th-item_source">
                    <img alt="filter"
                         src="/icons/filter.svg"
                         @click="emit('openFilters')"
                    />
                    <span>Источник</span>
                </th>
                <th v-for="(options, key) in fields">
                    <sort-select-button
                        v-model:field-for-chart="fieldForChartProxy"
                        :field="key"
                        v-model="sortProxy"
                        :without-chart="withoutChart"
                    >{{ options.title }}
                    </sort-select-button>
                </th>
            </tr>
            <tr v-if="!isEmpty(summary)">
                <th class="tree-table__th-item tree-table__th-item_summary">
                    <checkbox v-model:checked="isSelectedSummary"/>
                    Итого и среднее
                </th>

                <th class="tree-table__th-item"
                    v-for="(ignored, key) in fields"
                >
                    <percents-for-sorted
                        :field="key"
                        :metrics="summary"
                        :comparing="comparing"
                        :comparing-metrics="comparingSummary"
                        :fields="fields"
                    />
                </th>
            </tr>
            </thead>
            <tbody>
            <tree-table-items
                ref="elRootItems"
                :date-range="dateRange"
                :comparing-date-range="comparingDateRange"
                :comparing="comparing"
                :campaign="campaign"
                :summary-metrics="summary"
                :comparing-summary-metrics="comparingSummary"
                :sort-state="sort"
                :fields="fields"
                @update-summary-metrics="onUpdateSummaryMetrics"
                @update-comparing-summary-metrics="onUpdateComparingSummaryMetrics"
                v-model:items-for-chart="itemsForChartProxy"
                :without-chart="withoutChart"
                @loaded="onRootLoaded"
            />
            </tbody>
        </table>
    </div>
</template>

<script setup>
import TreeTableItems from "@/Pages/Campaign/Analytics/Components/TreeTableItems.vue";
import {computed, ref} from "vue";
import {isEmpty, r, isEmpty2, separateNumber} from "@/utils";
import SortSelectButton from "@/Pages/Campaign/Analytics/Components/SortSelectButton.vue";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import Checkbox from "@/Components/Checkbox.vue";
import PercentsForSorted from "@/Pages/Campaign/Analytics/Components/PercentsForSorted.vue";

const props = defineProps({
    dateRange: {
        type: String,
        required: true,
    },
    campaign: {
        type: Object,
        required: true,
    },

    fieldForChart: {
        type: [String, null],
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

    comparingDateRange: {
        type: String,
        required: false,
        default: '7days',
    },
    comparing: {
        type: Boolean,
        required: false,
        default: false,
    },

    fieldsSettings: {
        type: [Object, Array, null],
        required: false,
        default: null,
    },
});

// expenses, requests, clicks, cpc, cr, cpl, purchases, income, drr
const HEADER_FIELDS = {
    expenses: {
        title: 'Расходы',
        callback: (value) => `${separateNumber(r(value, 0))} ₽`,
    },
    requests: {
        title: 'Заявки',
        callback: (value) => separateNumber(r(value, 0)),
    },
    clicks: {
        title: 'Клики',
        callback: (value) => separateNumber(r(value, 0)),
    },
    cpc: {
        title: 'CPC',
        callback: (value) => `${separateNumber(r(value, 0))} ₽`,
        reverseColor: true,
    },
    cr: {
        title: 'CR',
        callback: (value) => `${separateNumber(r(value, 2))} %`,
    },
    cpl: {
        title: 'CPL',
        callback: (value) => `${separateNumber(r(value, 0))} ₽`,
        reverseColor: true,
    },
    purchases: {
        title: 'Продажи',
        callback: (value) => separateNumber(r(value, 0)),
    },
    income: {
        title: 'Доход',
        callback: (value) => `${separateNumber(r(value, 0))} ₽`,
    },
    drr: {
        title: 'ДРР',
        callback: (value) => `${separateNumber(r(value, 2))} %`,
        reverseColor: true,
    },
    impressions: {
        title: 'Показы',
        callback: (value) => separateNumber(r(value, 0)),
    },
    ctr: {
        title: 'CTR',
        callback: (value) => `${separateNumber(r(value, 2))} %`,
    },
};

const elRootItems = ref(null);
defineExpose({
    updateAllData: () => elRootItems.value.updateAllData(),
});

const rootItems = ref([]);

function onRootLoaded(items) {
    emit('rootLoaded', items);
    rootItems.value = items;
}

const fields = computed(() => {
    if (isEmpty2(props.fieldsSettings)) {
        return HEADER_FIELDS;
    }

    let res = {};

    let orderedList;
    if (!(props.fieldsSettings instanceof Array)) {
        orderedList = Object.keys(props.fieldsSettings)
            .sort((a, b) => props.fieldsSettings[a] - props.fieldsSettings[b])
    } else {
        orderedList = props.fieldsSettings;
    }

    for (let key of orderedList) {
        if (!isEmpty2(HEADER_FIELDS[key])) {
            res[key] = HEADER_FIELDS[key];
        }
    }

    return res;
});

function printSummary(summary, key, options) {
    let value = summary[key] ?? null;
    if (isEmpty2(value)) {
        return '-';
    }

    if (!isEmpty2(options.callback)) {
        return options.callback(value);
    }

    return value;
}

const emit = defineEmits([
    'update:fieldForChart',
    'update:itemsForChart',
    'openFilters',
    'rootLoaded',
]);

const fieldForChartProxy = computed({
    get: () => props.fieldForChart,
    set: (v) => emit('update:fieldForChart', v)
});

const itemsForChartProxy = computed({
    get: () => props.itemsForChart,
    set: (v) => emit('update:itemsForChart', v)
});

const summary = ref({});
const comparingSummary = ref({});
const sort = ref({
    field: 'expenses',
    direction: false,
});

const inLoading = ref(false);
const sortProxy = computed({
    get: () => sort.value,
    set: (val) => {
        inLoading.value = true;

        setTimeout(() => {
            sort.value = val;
            inLoading.value = false;
        }, 0);
    }
});

function onUpdateSummaryMetrics(value) {
    summary.value = value;
}

function onUpdateComparingSummaryMetrics(value) {
    comparingSummary.value = value;
}

const isSelectedSummary = computed({
    get: () => {
        if (itemsForChartProxy.value.length !== rootItems.value.length) {
            return false;
        }

        let selectedPaths = itemsForChartProxy.value.map((item) => item.path);

        for (let item of rootItems.value) {
            if (!selectedPaths.includes(item.path.join(','))) {
                return false;
            }
        }

        return true;
    },
    set: (val) => {
        if (val) {
            itemsForChartProxy.value = rootItems.value.map((item) => {
                return {
                    name: item.name,
                    path: item.path.join(','),
                }
            });
        } else {
            itemsForChartProxy.value = [];
        }
    }
});
</script>
