<template>
    <page-title title="Филиалы"/>

    <index
        :widgets="widgets"
        :project="project"
        :selected-widget="selectedWidget"
    >
        <h1 class="title">Отзывы</h1>
        <div>
            <period-controls
                class="mt-4"
                name="dateRange"
                :current-period-str="dateRangeFormat(dateRange, true, false)"
            >
                <div class="flex-grow"></div>
                <searchable-select
                    :options="[
                        {title: 'Группировать по 1 дню', value: '1day'},
                        {title: 'Группировать по 3 дня', value: '3days'},
                        {title: 'Группировать по 7 дней', value: '7days'},
                    ]"
                    @update:model-value="$inertia.visit(`?dateRange=${dateRange}&chartGroupType=${$event}`)"
                    :model-value="chartGroupType"
                    input-classes="select-sm"
                    class=" text-black/70 col-span-2 text-sm"
                />
            </period-controls>

            <div class="grid lg:grid-cols-5 md:grid-cols-2 gap-2 mt-8">
                <card-notification v-for="(opts, field) in fields">
                    <template #title>
                    <span class="text-[14px] font-normal">
                        {{ opts.title }}
                    </span>
                    </template>

                    <div class="flex flex-row">
                    <span class="font-bold text-base leading-5">
                        {{ opts.callback(summary[field]) }}
                    </span>
                        <div
                            class="ml-2 text-[10px] font-bold px-[6px]"
                            :class="colorPercent(field)"
                        >
                            {{ separateNumber(summaryDiff(field)) }}%
                        </div>
                    </div>

                    <template #footer>
                        <p class="mt-2" v-html="$t('campaigns.feed.footerNotification', {
                            preConv: opts.callback(summaryComparing[field]),
                            selConv: opts.callback(summary[field]),
                        })"></p>
                    </template>
                </card-notification>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2" v-if="!isEmpty2(charts)">
                <div class="p-4">
                    <h3 class="title-sm">Показы</h3>
                    <stats-chart
                        v-if="!isEmpty2(charts.views)"
                        :data="charts.views"
                    />
                </div>
                <div class="p-4">
                    <h3 class="title-sm">Клики</h3>
                    <stats-chart
                        v-if="!isEmpty2(charts.clicks)"
                        :data="charts.clicks"
                    />
                </div>
            </div>
        </div>
    </index>
</template>

<script setup>
import PeriodControls from "@/Components/Forms/PeriodControls.vue";
import CardNotification from "@/Pages/Campaign/Components/CardNotification.vue";
import {diffPercents, n, r, separateNumber, isEmpty2} from '@/utils.js';
import {dateRangeFormat} from '@/dateRange.js';
import StatsChart from "@/Pages/Reviews/Private/Components/StatsChart.vue";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";
import PageTitle from "@/Components/PageTitle.vue";
import Index from "@/Pages/SocialWidget/Index.vue";

const fields = {
    'views': {
        callback: (val) => `${val}`,
        title: 'Визиты',
    },
    'clicks': {
        callback: (val) => `${val}`,
        title: 'Клики',
    },
    'conversion': {
        callback: (val) => `${r(val)}%`,
        title: 'CTR',
    },
};

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    widgets: {
        type: Array,
        required: true,
    },
    selectedWidget: {
        type: Object,
        required: false,
        default: null,
    },

    dateRange: {
        required: true,
    },
    chartGroupType: {
        required: true,
    },

    summary: {
        type: Object,
        required: true,
    },
    summaryComparing: {
        type: Object,
        required: true,
    },

    charts: {
        type: Object,
        required: false,
        default: null,
    },
});

const colorPercent = (field) => {
    if (fields[field]?.reverse ?? false) {
        return {
            up: !isSummaryGrow(field),
            down: isSummaryGrow(field)
        }
    }

    return {
        up: isSummaryGrow(field),
        down: !isSummaryGrow(field)
    }
}

function isSummaryGrow(field) {
    return n(props.summary[field]) > n(props.summaryComparing[field]);
}

function summaryDiff(field) {
    return diffPercents(
        n(props.summaryComparing[field]),
        n(props.summary[field]),
    );
}
</script>

<style scoped>
/*noinspection CssUnusedSymbol*/
.up {
    background: #C3FAE8;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    color: #749988;
}

/*noinspection CssUnusedSymbol*/
.middle {
    background: #FFF3BF;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    color: #FBBC04;
}

/*noinspection CssUnusedSymbol*/
.down {
    background: #FFE3E3;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    color: #E1727E;
}
</style>
