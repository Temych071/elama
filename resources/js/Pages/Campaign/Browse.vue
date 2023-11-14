<template>
    <Head>
        <title>{{ $t('campaigns.browse.title') }}</title>
    </Head>

    <div class="metrika-feed">
        <div class="flex md:flex-row flex-col items-center space-y-2 flex-wrap">
            <period-controls name="dateRange"
                             :current-period-str="dateRangeStr"
                             :dont-send-form="true"
                             @change="onChangeDateRange"
                             class="flex-shrink-0"
            />
            <div class="flex-grow"/>
            <div class="controls md:w-auto w-full">
                <div class="browse-buttons">
                    <NavLink
                        :href="route('campaign.project_settings', campaign)"
                        class="btn btn-md btn-outline-base settings-btn mr-2">Настроить
                    </NavLink>

                    <form-button
                        v-if="data_update_available"
                        class="btn btn-md btn-outline-primary"
                        :action="dispatchFetchRoute"
                        method="post"
                        @callback="refresh"
                    >{{ $t('campaigns.feed.refresh') }}
                    </form-button>

                    <form-button
                        v-else-if="isDataFetching"
                        class="btn btn-md btn-primary"
                        disabled
                    >
                        <loading-spinner class="h-4 mr-1"/>
                        {{ $t('campaigns.feed.refreshLoading') }}
                    </form-button>

                    <div v-else>
                        <p class="text-center text-gray">
                            {{ $t('campaigns.feed.convsNotLoaded') }}
                        </p>
                    </div>
                    <tippy
                        v-else
                        :content="$t('campaigns.feed.nextRefreshAt', {at: nextRefreshAtFmt, freq: data_update_freq})"
                    >
                        <form-button
                            class="btn btn-md btn-outline-primary"
                            disabled
                        >{{ $t('campaigns.feed.refresh') }}
                        </form-button>
                    </tippy>
                </div>
            </div>
        </div>

        <div class="flex flex-row items-center justify-start">
            <div v-if="showAnalyticsSection" class="py-2 flex md:flex-row flex-col items-center">
                <period-controls name="comparingDateRange"
                                 :current-period-str="comparingDateRangeStr"
                                 :dont-send-form="true"
                                 @change="onChangeComparingDateRange"
                                 v-if="isInComparing"
                                 class="mr-2"
                                 :buttons="[]"
                />
                <button @click="switchComparing"
                        class="btn btn-sm btn-primary"
                        v-if="isInComparing"
                >Отменить сравнение
                </button>
                <button @click="switchComparing"
                        class="btn btn-sm btn-outline-base"
                        v-else
                >Сравнить периоды
                </button>

                <searchable-select
                    class="inline md:ml-2 md:mt-0 mt-2 text-sm text-black text-opacity-70"
                    input-classes="select-sm"
                    v-model="chartGroupType"
                    :options="[
                    {title: 'Группировать по дням', value: 'days-1'},
                    {title: 'Группировать по 3 дня', value: 'days-3'},
                    {title: 'Группировать по 7 дней', value: 'days-7'},
                    {title: 'Группировать по 30 дней', value: 'days-30'},
                    {title: 'Группировать по неделям', value: 'weeks'},
                    {title: 'Группировать по месяцам', value: 'months'},
                ]"
                />
            </div>
        </div>

        <div class="mb-2">
            <label class="text-xs text-black text-opacity-70">
                {{ $t('campaigns.balances.selectLabel') }}
            </label>
            <div class="flex md:flex-row flex-col justify-between space-y-2 md:space-y-0">
                <cabinet-balances-select
                    :campaign-id="activeCampaignId"
                    v-model:show-select="showBalanceSelector"
                />

                <div class="flex md:flex-row flex-col items-center md:space-x-2 space-y-2 md:space-y-0">
                    <button
                        class="btn btn-sm flex-shrink-0"
                        :class="{'btn-primary': showPlanFactSection}"
                        @click="toggleShowingPlanfact(!showPlanFactSection)"
                    >
                        План/Факт
                    </button>
                    <button
                        class="btn btn-sm"
                        :class="{'btn-primary': showAnalyticsSection}"
                        @click="toggleShowingAnalytics(!showAnalyticsSection)"
                    >
                        График
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showPlanFactSection">
            <plan-fact :campaign-id="activeCampaignId" class="mb-5" :period="dateRangeProxy"/>
            <hr class="mt-6 mb-4">
        </div>

        <div v-if="showAnalyticsSection">
            <analytics-chart :campaign="campaign"
                             :date-range="dateRangeProxy"
                             :comparing-date-range="comparingDateRangeProxy"
                             :field="selectedField"
                             :paths="selectedObjects"
                             :comparing="isInComparing"
                             :group-type="chartGroupType"
                             class="w-full py-4"
                             ref="elAnalyticsChart"
            />

            <hr class="my-12" v-if="campaign.notifications?.length">
        </div>

        <div class="mt-4 overflow-x-auto pb-4">
            <h2 class="mb-4 font-bold text-sm flex flex-row items-end">
                <span>Источники</span>
                <tippy content="Сравнить периоды">
                    <img
                        alt="compare"
                        src="/icons/colum.jpeg"
                        class="cursor-pointer inline w-5 mx-2 mb-[3px]"
                        @click="switchComparing"
                    />
                </tippy>
            </h2>

            <modal :show="showSettingsModal" max-width="sm" @close="showSettingsModal = false">
                <settings-modal :campaign-id="campaign" @saved="onSettingsSaved"/>
            </modal>

            <tree-table :date-range="dateRangeProxy"
                        :comparing-date-range="comparingDateRangeProxy"
                        :comparing="isInComparing"
                        :campaign="campaign"
                        v-model:field-for-chart="selectedField"
                        v-model:items-for-chart="selectedObjects"
                        class="w-full"
                        @open-filters="showSettingsModal = true"
                        :fields-settings="tableFieldsSettings"
                        @root-loaded="selectedObjects = $event.map((item) => {return {
                            path: item.path.join(','),
                            name: item.name,
                        }})"
                        ref="elAnalyticsTable"
            />
        </div>
        <feed-cards-list
            class="mt-12"
            :campaign-id="campaign.id"
            :campaign-notifications="campaign.notifications"
            :active-source-type="activeSourceType"
            :date-range="dateRangeProxy"
        />

        <section class="mb-8" v-if="settingsChecks.showSeoAudit">
            <h3 class="title mb-4 pl-2">SEO проверки</h3>
            <seo-audit-card :history="seoAuditHistory"/>
        </section>

        <section v-if="settingsChecks.showLinkChecker && !isEmpty(sourcesAuditSources)">
            <h2 class="title mb-4 pl-2">Проверка ссылок</h2>
            <ad-audit-card
                v-for="source in sourcesAuditSources"
                :item="source"
            />
        </section>
    </div>
</template>

<!--suppress CssUnusedSymbol -->
<style scoped>
.up {
    background: #C3FAE8;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    color: #749988;
}

.middle {
    background: #FFF3BF;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    color: #FBBC04;
}

.down {
    background: #FFE3E3;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    color: #E1727E;
}

.settings-btn {
    border: 1px solid #DCE4EA;
}
</style>

<script>
import layout from "@/Layouts/Authenticated.vue";

export default {layout};
</script>

<script setup>
import {router, Head, usePage} from '@inertiajs/vue3';
import PeriodControls from "@/Components/Forms/PeriodControls.vue";
import FormButton from "@/Components/Forms/FormButton.vue";
import LoadingSpinner from "@/Components/LoadingSpinner.vue";
import Tippy from "@/Components/Tippy.vue";
import PlanFact from "@/Pages/Campaign/Components/PlanFact.vue";
import {getLocale, parseParamsFromUrl, isEmpty} from '@/utils';
import CabinetBalancesSelect from "@/Pages/Campaign/Components/CabinetBalancesSelect.vue";
import NavLink from "@/Components/NavLink.vue";
import FeedCardsList from "@/Pages/Campaign/Components/FeedCardsList.vue";
import TreeTable from "@/Pages/Campaign/Analytics/Components/TreeTable.vue";
import Modal from "@/Components/Modal/Modal.vue";
import SettingsModal from "@/Pages/Campaign/Analytics/Components/SettingsModal.vue";
import {computed, onBeforeMount, onMounted, onUnmounted, ref, watch} from "vue";
import {dateRangeFormat, dateRangeToStr, getPreviousDateRange} from "@/dateRange";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";
import AnalyticsChart from "@/Pages/Campaign/Analytics/Components/AnalyticsChart.vue";
import AdAuditCard from "@/Pages/SeoAudits/Components/AdAuditCard.vue";
import SeoAuditCard from "@/Pages/SeoAudits/Components/SeoAuditCard.vue";

const props = defineProps({
    data_update_available: Boolean,
    data_update_freq: Number,
    data_next_refresh_at: String,
    data_status: String,
    activeSourceType: String,
    activeCampaignId: Number,
    balances: Array,
    campaign: {
        type: Object,
        required: true,
    },
    tableFieldsSettings: {
        type: [Array, Object, null],
        required: false,
        default: null,
    },
    dateRange: {
        type: [String, Array, Object],
        required: true,
    },
    comparingDateRange: {
        type: [String, Array, Object],
        required: true,
    },
    sourcesAuditSources: {
        type: Array,
        required: true,
    },
    seoAuditHistory: {
        type: Array,
        required: false,
        default: [],
    },
    settingsChecks: {
        type: Object,
        required: true,
    },
});

const elAnalyticsTable = ref(null);
const elAnalyticsChart = ref(null);

const showBalanceSelector = ref(true);
const refreshTimer = ref(null);
const refreshTimerFlag = ref(false)

const showSettingsModal = ref(false);
const pageUrl = usePage().url;

const isInComparing = ref(false);
const chartGroupType = ref('days-1');

const dateRangeStr = computed(() => dateRangeFormat(dateRangeProxy.value, true, false));
const comparingDateRangeStr = computed(() => dateRangeFormat(comparingDateRangeProxy.value, true, false))

const dateRange = ref();
const dateRangeProxy = computed({
    get: () => dateRangeToStr(dateRange.value ?? props.dateRange ?? '30days'),
    set: (val) => dateRange.value = val,
});

const comparingDateRange = ref();
const comparingDateRangeProxy = computed({
    get: () => dateRangeToStr(comparingDateRange.value ?? props.comparingDateRange ?? getPreviousDateRange(dateRangeProxy.value)),
    set: (val) => comparingDateRange.value = val,
});

const selectedField = ref('clicks');
watch(selectedField, updateUrl);

const selectedObjects = ref();

const showPlanFactSection = ref(true);
const showAnalyticsSection = ref(true);

function restoreShowingSections() {
    showPlanFactSection.value = localStorage.getItem('show.sections.planfact') === 'true';
    showAnalyticsSection.value = localStorage.getItem('show.sections.analytics') === 'true'
}

function toggleShowingPlanfact(value) {
    showPlanFactSection.value = value;
    localStorage.setItem('show.sections.planfact', value);
}

function toggleShowingAnalytics(value) {
    showAnalyticsSection.value = value;
    localStorage.setItem('show.sections.analytics', value);
}

onBeforeMount(() => {
    let params = parseParamsFromUrl(pageUrl);

    if (!isEmpty(params?.dateRange)) {
        dateRangeProxy.value = params.dateRange;
    }
    if (!isEmpty(params?.comparing)) {
        isInComparing.value = Boolean(params.comparing);
    }
    if (!isEmpty(params?.comparingDateRange)) {
        comparingDateRangeProxy.value = params.comparingDateRange;
    }
    if (!isEmpty(params?.chartField)) {
        selectedField.value = params.chartField;
    }
});

onMounted(async () => {
    if (isDataFetching.value) {
        refresh();
    }
    restoreShowingSections();
});

onUnmounted(() => {
    if (refreshTimer.value) {
        clearTimeout(refreshTimer.value);
    }
    refreshTimerFlag.value = true;
});

function onChangeDateRange(value) {
    dateRangeProxy.value = value;
    updateUrl();
}

function onChangeComparingDateRange(value) {
    comparingDateRangeProxy.value = value;
    updateUrl();
}

function updateUrl() {
    let query = `?dateRange=${dateRangeToStr(dateRangeProxy.value)}`;
    query += `&chartField=${selectedField.value}`;
    if (isInComparing.value) {
        query += `&comparing=${isInComparing.value}`;
        query += `&comparingDateRange=${dateRangeToStr(comparingDateRangeProxy.value)}`;
    }
    window.history.pushState("", "", query);
}

function switchComparing() {
    isInComparing.value = !isInComparing.value;
    updateUrl();
}

function onSettingsSaved() {
    showSettingsModal.value = false;
    router.reload({
        // Чтобы стриггерить watch и обновить данные
        //... возможно не работает))
        only: ['dateRange'],
    });
}

function convertPeriod(period) {
    return {
        from: new Date(period.from),
        to: new Date(period.to),
    }
}

function refresh() {
    refreshTimer.value = setTimeout(() => {
        router.reload({
            onSuccess() {
                if (refreshTimerFlag.value) {
                    return;
                }

                if (isDataFetching.value) {
                    refresh();
                } else {
                    elAnalyticsTable.value?.updateAllData();
                    elAnalyticsChart.value?.updateAllData();
                }
            }
        });
    }, 5000);
}

const minDate = computed(() => {
    return new Date(usePage().props.minDate);
})

const selPeriod = computed(() => {
    return convertPeriod(usePage().props.period);
})

const prePeriod = computed(() => {
    return convertPeriod(usePage().props.prevPeriod);
})

const selConv = computed(() => {
    return usePage().props.conversions;
})

const preConv = computed(() => {
    return usePage().props.prevConversions;
})

const isDataFetching = computed(() => {
    return props.data_status === 'fetching';
})

const nextRefreshAt = computed(() => {
    return new Date(props.data_next_refresh_at);
})
const nextRefreshAtFmt = computed(() => {
    return nextRefreshAt.value.toLocaleTimeString(getLocale(), {
        minute: '2-digit',
        hour: '2-digit',
    });
})

const dispatchFetchRoute = computed(() => {
    return route('campaign.fetch', usePage().props.activeCampaignId);
})
</script>
