<template>
    <page-title title="Аналитика - Отзывы"/>

    <authenticated>
        <h1 class="title">Аналитика</h1>
        <div>
            <period-controls
                class="mt-4"
                name="dateRange"
                :current-period-str="dateRangeFormat(dateRange, true, false)"
                :buttons="[
                    {title: '7 дней', value: '7days'},
                    {title: '30 дней', value: '30days'},
                    {title: '90 дней', value: '90days'},
                    {title: '180 дней', value: '180days'},
                    {title: '1 год', value: '365days'},
                ]"
                dont-send-form
                @change="onSelectDateRange"
            >
                <div class="flex-grow"></div>
                <searchable-select
                    :options="[
                        {title: 'Группировать по 1 дню', value: '1day'},
                        {title: 'Группировать по 3 дня', value: '3days'},
                        {title: 'Группировать по 7 дней', value: '7days'},
                    ]"
                    @update:model-value="router.get('', {chartGroupType: $event})"
                    :model-value="chartGroupType"
                    input-classes="select-sm"
                    class=" text-black/70 col-span-2"
                />
            </period-controls>

            <div class="grid lg:grid-cols-5 md:grid-cols-3 sm:grid-cols-2 gap-2 mt-8">
                <card-notification v-for="(opts, field) in fields">
                    <template #title>
                    <span class="text-[14px] font-normal flex items-center gap-1">
                        <span>{{ opts.title }}</span>
                        <info-icon
                            v-if="!isEmpty2(opts.tip)"
                            :tooltip="opts.tip"
                            class="h-3.5"
                        />
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

            <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2" v-if="!isEmpty2(charts)">
                <div class="p-4">
                    <h3 class="title-sm">Посещение формы для оценки</h3>
                    <stats-chart
                        v-if="!isEmpty2(charts.views)"
                        :data="charts.views"
                    />
                </div>
                <div class="p-4">
                    <h3 class="title-sm">Поставлено оценок</h3>
                    <stats-chart
                        v-if="!isEmpty2(charts.reviews)"
                        :data="charts.reviews"
                    />
                </div>
                <div class="p-4">
                    <h3 class="title-sm">Новые отзывы</h3>
                    <stats-chart
                        v-if="!isEmpty2(charts.external_reviews)"
                        :data="charts.external_reviews"
                    />
                </div>
            </div>

            <div class="grid lg:grid-cols-2 grid-cols-1 px-4 gap-8">
                <div>
                    <div class="flex flex-row gap-4 items-center mb-1">
                        <h3 class="title-sm">Рейтинг</h3>
                        <div class="flex flex-row gap-3 text-sm">
                            <button
                                v-for="sourceType in sourceTypes"
                                @click="changeRatingSource(sourceType.type)"
                                class="flex items-center gap-x-1 border-gray-300 px-1 py-0.5 rounded-lg"
                                :class="`${ratingSource === sourceType.type ? 'border' : ''}`"
                            >
                                <img v-if="!isEmpty2(sourceType.icon)" alt="icon" :src="sourceType.icon"/>
                                <span>{{ sourceType.title }}</span>
                            </button>
                        </div>
                    </div>
                    <stats-multi-chart :data="props.ratingChart" :min="0" :max="5" :step="1"/>
                </div>
                <div>
                    <div class="title-sm flex gap-1 items-end mb-2.5">
                        <h3>Филиалы</h3>
                        <img
                            class="h-4 cursor-pointer mb-1"
                            alt="compare"
                            src="/icons/colum.jpeg"
                            @click="router.get('', {compareAllStats: !compareAllStats})"
                        />
                    </div>
                    <div class="card p-4 overflow-x-auto">
<!--                        <table class="table-plan-campaigns w-full text-left">-->
<!--                            <thead>-->
<!--                            <tr>-->
<!--                                <th>Место</th>-->
<!--                                <th>Компания</th>-->
<!--                                <th>Рейтинг</th>-->
<!--                                <th>Переходы</th>-->
<!--                                <th>Маршруты</th>-->
<!--                                <th>Звонки</th>-->
<!--                                <th>На сайт</th>-->
<!--                            </tr>-->
<!--                            </thead>-->
<!--                            <tbody class="text-sm">-->
<!--                            <tr v-for="(form, rank) in formsStats" :key="form.name">-->
<!--                                <td>{{ rank + 1 }}</td>-->
<!--                                <td>{{ form.name }}</td>-->
<!--                                <td class="flex items-center gap-x-1">{{ r(form.rating, 2) }} <star filled class="inline w-4" /></td>-->
<!--                                <td>{{ form.views }}</td>-->
<!--                                <td>{{ form.routes }}</td>-->
<!--                                <td>{{ form.calls }}</td>-->
<!--                                <td>{{ form.site }}</td>-->
<!--                            </tr>-->
<!--                            </tbody>-->
<!--                        </table>-->
                        <table class="table-plan-campaigns">
                            <thead>
                            <tr>
                                <th>Место</th>
                                <th>Название</th>
<!--                                <th>Адрес</th>-->
                                <th>Яндекс</th>
                                <th>2ГИС</th>
                                <th>Отзывы</th>
                                <th>Оценки</th>
                                <th>Ответы</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in allStats">
                               <td><compared-value
                                   :value="item.rank"
                                   calc-func="sub"
                                   reverse-color
                                   reverse-arrow
                               /></td>
                               <td class="max-w-[200px] break-words">{{ item.name }}</td>
<!--                               <td>{{ item.address }}</td>-->
                               <td><compared-value
                                   :cmp-value="allStatsCompare[item.name]?.ratingYandex ?? null"
                                   :value="r(item.ratingYandex)"
                                   calc-func="sub"
                                   if-empty="-"
                               /></td>
                               <td><compared-value
                                   :cmp-value="allStatsCompare[item.name]?.rating2gis ?? null"
                                   :value="r(item.rating2gis)"
                                   calc-func="sub"
                                   if-empty="-"
                               /></td>
                               <td><compared-value
                                   :cmp-value="allStatsCompare[item.name]?.reviewsCount ?? null"
                                   :value="item.reviewsCount"
                                   calc-func="sub"
                               /></td>
                               <td><compared-value
                                   :cmp-value="allStatsCompare[item.name]?.marksCount ?? null"
                                   :value="item.marksCount"
                                   calc-func="sub"
                               /></td>
                               <td><compared-value
                                   :cmp-value="allStatsCompare[item.name]?.answersCount ?? null"
                                   :value="item.answersCount"
                                   calc-func="percent"
                               /></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center flex-wrap gap-2" v-if="reviewForms">
                <nav-link
                    v-for="form in reviewForms" :key="form.id"
                    class="btn btn-sm"
                    :class="`${form.id === reviewForm?.id ? 'btn-primary' : 'btn-base'}`"
                    :href="route('reviews.private.stats.show', {
                        campaign: route()?.params?.campaign,
                        reviewForm: form.id,
                        dateRange: dateRange
                    })"
                >{{ form.name }}
                </nav-link>
                <nav-link
                    class="btn btn-sm mx-1"
                    :class="`${!reviewForm ? 'btn-primary' : 'btn-base'}`"
                    :href="route('reviews.private.stats.show', {
                        campaign: route()?.params?.campaign,
                        dateRange: dateRange,
                    })"
                >Все
                </nav-link>
            </div>

            <hr class="my-4">

            <div class="flex flex-row items-center" v-if="reviewForm">
                <input
                    type="text"
                    :value="route('reviews.public.show', reviewForm.slug)"
                    class="rounded-xl px-2 border-gray-200 text-sm py-0.5 ml-2 w-64 text-gray-800"
                    readonly
                    @focus="$event.target.select()"
                    ref="elFormLink"
                />
                <img
                    class="w-4 h-4 ml-2 cursor-pointer"
                    alt="copy"
                    src="/icons/copy.svg"
                    @click="copyLink"
                >
            </div>

            <div
                v-if="true || reviewForm"
                class="mt-4"
            >
                <h3 class="title-sm">Виджеты</h3>
                <div class="flex flex-row flex-wrap">
                    <div
                        v-for="widgetIndex in yamapsWidgets"
                        :key="widgetIndex"
                        style="width:400px;height:650px;overflow:hidden;position:relative;"
                        class="m-2 flex-shrink-0"
                    >
                        <iframe
                            style="width:100%;height:100%;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box"
                            :src="`https://yandex.ru/maps-reviews-widget/${widgetIndex}?comments`"
                        ></iframe>
                    </div>
                    <universal-reviews-widget
                        class="w-[400px] h-[650px] relative m-2 flex-shrink-0"
                        v-for="widget in widgets"
                        :key="`${widget.title}-${widget.widgetData}`"
                        :widget-data="widget.widgetData"
                        :title="widget.title"
                    />
                </div>
            </div>
<!--            <div class="card p-4" v-if="isEmpty2(reviewForm)">-->
<!--                <table class="table-plan-campaigns">-->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                        <th>Место</th>-->
<!--                        <th>Название</th>-->
<!--                        <th>Адрес</th>-->
<!--                        <th>Яндекс</th>-->
<!--                        <th>2ГИС</th>-->
<!--                        <th>Отзывы</th>-->
<!--                        <th>Оценки</th>-->
<!--                        <th>Ответы</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    <tr v-for="(item, i) in allStats">-->
<!--                       <td><compared-value :value="i + 1" calc-func="sub"/></td>-->
<!--                       <td>{{ item.name }}</td>-->
<!--                       <td>{{ item.address }}</td>-->
<!--                       <td><compared-value :value="r(item.ratingYandex)" calc-func="sub" if-empty="-"/></td>-->
<!--                       <td><compared-value :value="r(item.rating2gis)" calc-func="sub" if-empty="-"/></td>-->
<!--                       <td><compared-value :value="item.reviewsCount" calc-func="percent"/></td>-->
<!--                       <td><compared-value :value="item.marksCount" calc-func="percent"/></td>-->
<!--                       <td><compared-value :value="item.answersCount" calc-func="percent"/></td>-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                </table>-->
<!--            </div>-->
        </div>
    </authenticated>
</template>

<script setup>
import Authenticated from "@/Layouts/Authenticated.vue";
import PeriodControls from "@/Components/Forms/PeriodControls.vue";
import CardNotification from "@/Pages/Campaign/Components/CardNotification.vue";
import {diffPercents, n, r, separateNumber, isEmpty2} from '@/utils.js';
import {dateRangeFormat} from '@/dateRange.js';
import NavLink from "@/Components/NavLink.vue";
import StatsChart from "@/Pages/Reviews/Private/Components/StatsChart.vue";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";
import PageTitle from "@/Components/PageTitle.vue";
import {computed, onMounted, ref} from "vue";
import UniversalReviewsWidget from "@/Pages/Reviews/Private/Components/UniversalReviewsWidget.vue";
import StatsMultiChart from "@/Pages/Reviews/Private/Components/StatsMultiChart.vue";
import {router} from "@inertiajs/vue3";
import ComparedValue from "@/Pages/Reviews/Private/Components/ComparedValue.vue";
import InfoIcon from "@/Pages/Reviews/Components/InfoIcon.vue";

const fields = {
    'views': {
        callback: (val) => `${val}`,
        title: 'Посещения формы',
    },
    'reviews': {
        callback: (val) => `${val}`,
        title: 'Поставлено оценок',
    },
    'external_reviews': {
        callback: (val) => `${val}`,
        title: 'Новые отзывы',
    },
    'conversion': {
        callback: (val) => `${r(val)}%`,
        title: 'Конверсия',
    },
    'rating': {
        callback: (val) => `${r(val, 2)}`,
        title: 'Рейтинг',
        tip: 'Общий рейтинг на Яндекс Картах и 2ГИС',
    },
};

const sourceTypes = [
    {
        type: 'yandex',
        title: 'Яндекс.Карты',
        icon: '/icons/reviews/map_yandex.svg',
    },
    {
        type: 'double-gis',
        title: '2ГИС',
        icon: '/icons/reviews/map_2gis.svg',
    },
];

const props = defineProps({
    reviewForm: {
        type: Object,
        required: false,
        default: null,
    },
    reviewForms: {
        type: Array,
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

    formsStats: {
        type: Array,
        required: false,
        default: [],
    },
    compareFormsStats: {
        type: Boolean,
        required: false,
        default: false,
    },

    ratingSource: {
        type: String,
        required: true,
    },
    ratingChart: {
        type: Object,
        required: false,
        default: {},
    },

    allStats: {
        type: Array,
        required: false,
        default: [],
    },
    compareAllStats: {
        type: Boolean,
        required: false,
        default: false,
    },
    allStatsCompare: {
        type: Object,
        required: false,
        default: {},
    },
});

function onSelectDateRange(dateRange) {
    router.get('', {
        dateRange: dateRange,
        chartGroupType: {
            '7days': '1day',
            '30days': '3days',
            '90days': '3days',
            '180days': '7days',
            '365days': '7days',
        }[dateRange] ?? props.chartGroupType ?? '1day',
    });
}

function changeRatingSource(source) {
    if (source !== props.ratingSource) {
        router.get('', {'ratingSource': source});
    }
}

const widgets = ref([]);

onMounted(() => {
    loadWidgets();
});

async function loadWidgets() {
    let forms = [];
    if (!isEmpty2(props.reviewForm)) {
        forms.push(props.reviewForm);
    } else {
        forms.push(...props.reviewForms);
    }

    for (let form of forms) {
        const formWidgets = (await axios.get(route('reviews.private.stats.widgets', [form.project_id, form.id]))).data;
        for (let widget of formWidgets) {
            widgets.value.push({
                title: form.name,
                widgetData: widget,
            });
        }
    }

    return widgets.value;
}

const yamapsWidgets = computed(() => {
    if (!isEmpty2(props.reviewForm)) {
        if (!isEmpty2(props.reviewForm.widget_yamaps)) {
            return [props.reviewForm.widget_yamaps];
        } else {
            return [];
        }
    } else {
        return props.reviewForms.map((reviewForm) => reviewForm.widget_yamaps ?? null).filter((val) => !isEmpty2(val));
    }
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

const elFormLink = ref(null);

function copyLink() {
    navigator.clipboard.writeText(elFormLink.value.value);
    elFormLink.value.select();
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
