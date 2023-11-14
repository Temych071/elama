<template>
    <private-loyalty-layout :loyalties="loyalties" :loyalty="loyalty">
        <period-controls
            name="dateRange"
            :current-period-str="dateRangeFormat(dateRange, true)"
        >
            <div class="flex-grow"></div>
            <searchable-select
                :options="GROUP_BY_ITEMS"
                @update:model-value="useForm({chartGroupType: $event}).get('')"
                :model-value="chartGroupType"
                input-classes="select-sm"
                class=" text-black/70 col-span-2 text-sm"
            />
        </period-controls>

        <div class="grid 2xl:grid-cols-5 xl:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 gap-2 mt-8">
            <div v-for="(opts, field) in CARDS" class="flex flex-col items-stretch gap-y-2">
                <card-notification>
                    <template #title>
                        <span class="font-semibold">
                            {{ opts.title }}
                        </span>
                        <info-icon v-if="opts.info" :tooltip="opts.info" class="w-3 ml-2"/>
                    </template>

                    <div class="flex flex-row">
                        <span class="font-bold text-base leading-5">
                            {{ opts.callback(summary[field].summary) }}
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
                            preConv: opts.callback(summaryPrev[field].summary),
                            selConv: opts.callback(summary[field].summary),
                        })"></p>

                        <p class="mt-2 text-gray-500">
                            <span>{{ opts.footerLabel }}: </span>
                            <span class="font-bold">{{ opts.footerValueCallback(summary[field]) }}</span>
                        </p>
                    </template>
                </card-notification>

                <stats-chart :data="summary[field].chart" :value-callback="opts.callback"/>
            </div>
        </div>

        <hr/>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 py-4">
            <div class="row-span-2 rounded-xl p-4">
                <h3 class="text-lg font-bold mb-2">Выручка</h3>
                <stats-chart
                    class="h-96"
                    :data="summary.revenue.chart"
                    :value-callback="val => `${separateNumber(val / 100)} ₽`"
                    show-ticks
                />
            </div>
            <div class="border border-card-border drop-shadow-card bg-card-surface rounded-xl p-4">
                <table class="table-plan-campaigns">
                    <thead>
                    <tr>
                        <th>Сегмент</th>
                        <th>Выручка</th>
                        <th>Средний чек</th>
                        <th>CPO</th>
                    </tr>
                    </thead>
                    <tbody v-if="!isEmpty2(segments) && !isEmpty2(segments?.total?.summary)">
                    <tr class="font-bold">
                        <td>{{ segments.total.title }}</td>
                        <td class="max-w-[100px]">
                            <stats-value-bar
                                :max="segments.total.summary.revenue"
                                :value="segments.total.summary.revenue"
                                :display-callback="val => `${separateNumber(val)} ₽`"
                            />
                        </td>
                        <td>{{ separateNumber(segments.total.summary.avg_cheque) }} ₽</td>
                        <td>{{ separateNumber(segments.total.summary.cpo) }} ₽</td>
                    </tr>
                    <tr v-for="segment in segments.segments">
                        <td class="flex items-center gap-x-1">
                            <span>{{ segment.title }}</span>
                            <info-icon class="w-3 text-gray-600" :tooltip="segment.desc"/>
                        </td>
                        <td class="max-w-[50px]">
                            <stats-value-bar
                                :max="segments.total.summary.revenue"
                                :value="segment.summary.revenue"
                                :display-callback="val => `${separateNumber(val)} ₽`"
                            />
                        </td>
                        <td>{{ separateNumber(segment.summary.avg_cheque) }} ₽</td>
                        <td>{{ separateNumber(segment.summary.cpo) }} ₽</td>
                    </tr>
                    </tbody>
                    <tr v-else><td colspan="4" class="text-center text-gray-500">Нет данных</td></tr>
                </table>
            </div>
            <div class="border border-card-border drop-shadow-card bg-card-surface rounded-xl p-4" v-if="false">
                <table class="table-plan-campaigns">
                    <thead>
                    <tr>
                        <th>Источник</th>
                        <th>Переходы</th>
                        <th>Регистрации</th>
                        <th>Конверсия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="font-bold">
                        <td>Все</td>
                        <td>{{ separateNumber(1135) }}</td>
                        <td>{{ separateNumber(325) }}</td>
                        <td>{{ 35 }}%</td>
                    </tr>
                    <tr>
                        <td>QR Код кафе</td>
                        <td>{{ separateNumber(835) }}</td>
                        <td>{{ separateNumber(211) }}</td>
                        <td>{{ 18 }}%</td>
                    </tr>
                    <tr>
                        <td>Баннер на сайте</td>
                        <td>{{ separateNumber(458) }}</td>
                        <td>{{ separateNumber(125) }}</td>
                        <td>{{ 12 }}%</td>
                    </tr>
                    <tr>
                        <td>Вконтакте</td>
                        <td>{{ separateNumber(211) }}</td>
                        <td>{{ separateNumber(80) }}</td>
                        <td>{{ 34 }}%</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row-span-2 border border-card-border drop-shadow-card bg-card-surface rounded-xl p-4">
                <h3 class="text-lg font-bold">Качество клиентской базы</h3>
                <table class="table-plan-campaigns mt-4">
                    <tbody>
                    <tr v-for="field in CLIENTS_QUALITY_FIELDS">
                        <td>{{ field.title }}</td>
                        <td>
                            <stats-value-bar
                                :max="Math.max(...Object.values(props.clientsQuality))"
                                :value="clientsQuality[field.key] ?? 0"
                            />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row-span-2 border border-card-border drop-shadow-card bg-card-surface rounded-xl p-4">
                <table class="table-plan-campaigns">
                    <thead>
                    <tr>
                        <th class="text-xl">Демография</th>
                        <td class="text-sm">
                            <span>Ж </span>
                            <span class="font-bold">{{ separateNumber(genders.female ?? 0) }}</span>
                            ({{ r(genders.female / genders.total * 100, 0) }}%)
                        </td>
                        <td class="text-sm">
                            <span>М </span>
                            <span class="font-bold">{{ separateNumber(genders.male ?? 0) }}</span>
                            ({{ r(genders.male / genders.total * 100, 0) }}%)
                        </td>
                    </tr>
                    </thead>
                    <tbody v-if="!isEmpty2(groupedAges)">
                    <tr v-for="ageGroup in groupedAges">
                        <td>{{ ageGroup.title }}</td>
                        <td colspan="2">
                            <stats-value-bar :max="maxAgeValue" :value="ageGroup.value"/>
                        </td>
                    </tr>
                    </tbody>
                    <tr v-else>
                        <td colspan="3" class="text-center text-gray-500 italic">Нет данных</td>
                    </tr>
                </table>
            </div>
        </div>
    </private-loyalty-layout>
</template>

<script setup>
import PrivateLoyaltyLayout from "@/Pages/Loyalty/Layouts/PrivateLoyaltyLayout.vue";
import PeriodControls from "@/Components/Forms/PeriodControls.vue";
import {dateRangeFormat} from "@/dateRange";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";
import {diffPercents, isEmpty2, n, r, separateNumber} from "@/utils";
import CardNotification from "@/Pages/Campaign/Components/CardNotification.vue";
import InfoIcon from "@/Pages/Reviews/Components/InfoIcon.vue";
import StatsChart from "@/Pages/Loyalty/Components/StatsChart.vue";
import {useForm} from "@inertiajs/vue3";
import {range} from "lodash";
import {computed} from "vue";
import StatsValueBar from "@/Pages/Loyalty/Components/StatsValueBar.vue";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    loyalties: {
        type: Array,
        required: true,
    },

    dateRange: {
        type: Object,
        required: true,
    },
    chartGroupType: {
        type: String,
        required: false,
        default: '1day',
    },

    summary: {
        type: Array,
        required: false,
        default: {},
    },
    summaryPrev: {
        type: Array,
        required: false,
        default: {},
    },

    genders: {
        type: Object,
        required: false,
        default: () => ({
            male: 0,
            female: 0,
            total: 0,
        }),
    },
    ages: {
        type: Object,
        required: false,
        default: {},
    },

    clientsQuality: {
        type: Object,
        required: false,
        default: {total: 0},
    },

    segments: {
        type: Object,
        required: false,
        default: null,
    },
});

const GROUP_BY_ITEMS = [
    {title: 'Группировать по 1 дню', value: '1day'},
    {title: 'Группировать по 3 дня', value: '3days'},
    {title: 'Группировать по 7 дней', value: '7days'},
];

const CARDS = {
    'revenue': {
        callback: (val) => `${separateNumber(val / 100)} ₽`,
        title: 'Выручка',
        info: 'Какая-то информация',
        footerLabel: 'Доля от общей выручки',
        footerValueCallback: (item) => `${r((item.total ? item.summary / item.total : 100) * 100, 2)}%`,
    },
    'expenses': {
        callback: (val) => `${separateNumber(val / 100)} ₽`,
        title: 'Расходы',
        info: 'Какая-то информация',
        reverse: true,
        footerLabel: 'Доля расходов к выручке',
        footerValueCallback: (item) => `${r((item.revenue ? item.summary / item.revenue : 100) * 100, 2)}%`,
    },
    'cards_count': {
        callback: (val) => `${separateNumber(val)}`,
        title: 'Установлено карт',
        info: 'Какая-то информация',
        footerLabel: 'Всего карт выдано',
        footerValueCallback: (item) => `${separateNumber(item.total)}`,
    },
    'usage_percent': {
        callback: (val) => `${r(val * 100)}%`,
        title: 'Процент применений',
        info: 'Какая-то информация',
        footerLabel: 'Среднее значение за год',
        footerValueCallback: (item) => `${separateNumber(r(item.perYear * 100, 2))}%`,
    },
};

const AGE_GROUPS = [
    {
        ages: range(0, 17),
        title: '< 18 лет',
    },
    {
        ages: range(18, 24),
        title: '18-24 лет',
    },
    {
        ages: range(25, 30),
        title: '25-30 лет',
    },
    {
        ages: range(30, 34),
        title: '30-34 лет',
    },
    {
        ages: range(35, 39),
        title: '35-39 лет',
    },
    {
        ages: range(40, 44),
        title: '40-44 лет',
    },
    {
        ages: range(45, 49),
        title: '45-49 лет',
    },
    {
        ages: range(50, 100),
        title: '50+ лет',
    },
];

const CLIENTS_QUALITY_FIELDS = [
    {
        title: 'Заполнено имя',
        key: 'name',
    },
    {
        title: 'Заполнен пол',
        key: 'gender',
    },
    {
        title: 'Заполнен день рождения',
        key: 'birthday',
    },
    {
        title: 'Заполнен E-Mail',
        key: 'email',
    },
    {
        title: 'Согласны на рассылку',
        key: 'sms_notifications',
    },
    {
        title: 'Согласны на рассылку E-Mail',
        key: 'email_notifications',
    },
];

const groupedAges = computed(() => {
    return AGE_GROUPS.map(group => ({
        title: group.title,
        value: group.ages.reduce((acc, val) => acc + Number(props.ages[val] ?? 0), 0),
    })).filter(group => group.value > 0);
});
const maxAgeValue = computed(() => {
    let res = 0;
    for (let group of groupedAges.value) {
        if (group.value > res) {
            res = group.value;
        }
    }
    console.log(res);
    return res;
});

const colorPercent = (field) => {
    if (CARDS[field]?.reverse ?? false) {
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
    return n(props.summary[field].summary) > n(props.summaryPrev[field].summary);
}

function summaryDiff(field) {
    if (isEmpty2(props.summaryPrev[field].summary)) {
        return 100;
    }

    return diffPercents(
        n(props.summaryPrev[field].summary),
        n(props.summary[field].summary),
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
