<template>
    <tr v-show="!hidden">
        <td :style="{'padding-left': `${8 + (data.path.length - 1) * 12}px`}">
            <div class="tree-table__td-item__source">
                <template v-if="!data.end">
                    <span @click="shownNested = 'shown'"
                          v-if="shownNested !== 'shown'"
                          class="extend-btn"
                    >+</span>
                    <span @click="shownNested = 'hide'"
                          v-else
                          class="extend-btn"
                    >-</span>
                </template>
                <!--Костыль))-->
                <span v-else class="extend-btn-hidden">+</span>

                <checkbox v-if="!withoutChart" v-model:checked="isSelected"/>
                <tree-table-item-icon :path="data.path" class="mr-2 w-4"/>
                <span class="flex-grow">{{ data.name }}</span>
                <a
                    v-if="data.url"
                    :href="data.url"
                    target="_blank"
                    class="flex-shrink-0 w-3"
                >
                    <img alt="link" src="/icons/analytics-source-link.svg" />
                </a>
            </div>
        </td>

        <template v-for="(ignored, key) in fields">
            <td class="tree-table__th-item">
                <percents-for-sorted :field="key"
                                     :metrics="data.metrics"
                                     :sort-state="sortState"
                                     :summary="summaryMetrics"
                                     :first="firstMetrics ?? data.metrics"
                                     :comparing="comparing"
                                     :comparing-metrics="comparingData.metrics"
                                     :fields="fields"
                />
            </td>
        </template>

    </tr>
    <template v-if="shownNested !== 'none'">
        <tree-table-items :path="data.path"
                          :date-range="dateRange"
                          :campaign="campaign"
                          :hidden="shownNested !== 'shown' || hidden"
                          :summary-metrics="summaryMetrics"
                          :sort-state="sortState"
                          v-model:items-for-chart="itemsForChartProxy"
                          :comparing-date-range="comparingDateRange"
                          :comparing="comparing"
                          :comparing-summary-metrics="comparingSummaryMetrics"
                          :fields="fields"
                          :comparing-root-metrics="comparingRootMetrics ?? comparingData.metrics"
                          :without-chart="withoutChart"
        />
    </template>
</template>

<script setup>
import {ref, computed} from 'vue';
import TreeTableItems from "@/Pages/Campaign/Analytics/Components/TreeTableItems.vue";
import TreeTableItemIcon from "@/Pages/Campaign/Analytics/Components/TreeTableItemIcon.vue";
import PercentsForSorted from "@/Pages/Campaign/Analytics/Components/PercentsForSorted.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {diffPercents, isEmpty2, n} from "@/utils";

const props = defineProps({
    data: {
        type: Object,
        required: true,
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
    firstMetrics: {
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
    comparingData: {
        type: Object,
        required: false,
        default: {},
    },
    comparingSummaryMetrics: {
        type: Object,
        required: false,
        default: {},
    },
});

const emit = defineEmits(['update:itemsForChart']);

const itemsForChartProxy = computed({
    get: () => props.itemsForChart,
    set: (v) => emit('update:itemsForChart', v),
});

const shownNested = ref('none');

const pathStr = computed(() => props.data.path.join(','));

const isSelected = computed({
    get: () => props.itemsForChart.filter((i) => i.path === pathStr.value).length > 0,
    set: (v) => {
        if (v) {
            if (props.itemsForChart.length >= 5) {
                alert('Одновременно может быть выбрано не более пяти элементов.');
                return;
                // TODO: Почему-то всё ровно чекбокс отмечается
            }

            emit('update:itemsForChart', [...props.itemsForChart, {
                path: pathStr.value,
                name: props.data.name,
            }]);
        } else {
            emit('update:itemsForChart', props.itemsForChart.filter((i) => i.path !== pathStr.value));
        }
    }
});

function printFieldDiff(main, comparing, key) {
    if (isEmpty2(comparing)) {
        return '-';
    }

    let mainValue = n(main[key] ?? 0);
    let comparingValue = n(comparing[key] ?? 0);

    if (isEmpty2(mainValue) && isEmpty2(comparingValue)) {
        return '-';
    }

    if (isEmpty2(mainValue)) {
        return '-100 %';
    }

    if (isEmpty2(comparingValue)) {
        return '100 %';
    }

    return `${diffPercents(comparingValue, mainValue, true)} %`
}

function getFieldDiffDir(main, comparing, key, options) {
    if (isEmpty2(comparing)) {
        return null;
    }

    let mainValue = n(main[key]);
    let comparingValue = n(comparing[key]);
    let reverse = options?.reverse ?? false;

    if (
        (isEmpty2(mainValue) && isEmpty2(comparingValue))
        || mainValue === comparingValue
    ) {
        return null;
    }

    let res = mainValue > comparingValue;
    if (reverse) {
        res = !res;
    }

    return res;
}

function getFieldDiffBgColor(main, comparing, key, options) {
    switch (getFieldDiffDir(main, comparing, key, options)) {
        case true:
            return '#DFFAE6'
        case false:
            return '#FFC9C9';
        default:
            return '#FFFDEC';
    }
}

function getFieldDiffColor(main, comparing, key, options) {
    switch (getFieldDiffDir(main, comparing, key, options)) {
        case true:
            return '#749988'
        case false:
            return '#966A6A';
        default:
            return '#F8C635';
    }
}

</script>
