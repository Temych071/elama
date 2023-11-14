<template>
    <tr v-show="!hidden">
        <td :style="{'padding-left': `${8 + (data.path.length - 1) * 12}px`}">
            <div class="flex flex-row justify-start items-center">
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

                <tree-table-item-icon :path="data.path" class="mr-2 w-4" />
                <span>{{ data.name }}</span>
            </div>
        </td>

        <template v-for="(ignored, key) in fields">
            <td class="projects-list__th-item">
                <percents-for-sorted :field="key"
                                     :metrics="data.metrics"
                                     :comparing-metrics="comparingData.metrics"
                                     :comparing="comparing"
                                     :fields="fields"
                />
            </td>
        </template>

    </tr>
    <template v-if="shownNested !== 'none'">
        <tree-table-items :path="data.path"
                          :date-range="dateRange"
                          :project="project"
                          :hidden="shownNested !== 'shown' || hidden"
                          :sort-state="sortState"
                          :comparing-date-range="comparingDateRange"
                          :comparing="comparing"
                          :fields="fields"
        />
    </template>
</template>

<script setup>
import {ref, computed} from 'vue';
import {diffPercents, isEmpty2, n} from "@/utils";
import TreeTableItems from "@/Pages/Campaign/Components/ProjectsList/TreeTableItems.vue";
import TreeTableItemIcon from "@/Pages/Campaign/Components/ProjectsList/TreeTableItemIcon.vue";
import PercentsForSorted from "@/Pages/Campaign/Components/ProjectsList/PercentsForSorted.vue";

const props = defineProps({
    data: {
        type: Object,
        required: true,
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
    comparingData: {
        type: Object,
        required: false,
        default: {},
    },
});

const shownNested = ref('none');

const pathStr = computed(() => props.data.path.join(','));

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
        return '100 %';
    }

    if (isEmpty2(comparingValue)) {
        return '100 %';
    }

    return `${diffPercents(comparingValue, mainValue, false)} %`
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
