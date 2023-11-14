<template>
    <page-title title="Аналитика"/>

    <div class="analytics-show mb-60">
        <period-controls name="dateRange"
                         :current-period-str="dateRangeStr"
                         :dont-send-form="true"
                         @change="onChangeDateRange"
        />
        <period-controls name="comparingDateRange"
                         :current-period-str="comparingDateRangeStr"
                         :dont-send-form="true"
                         @change="onChangeComparingDateRange"
                         v-if="isInComparing"
                         class="mt-2"
                         :buttons="[]"
        />
        <div class="py-2 flex md:flex-row flex-col">
            <button @click="switchComparing"
                    class="btn btn-md btn-primary"
                    v-if="isInComparing"
            >Отменить сравнение</button>
            <button @click="switchComparing"
                    class="btn btn-md btn-outline-base"
                    v-else
            >Сравнить периоды</button>
<!--            <button @click="showSettingsModal = true"-->
<!--                    class="btn btn-md btn-outline-base ml-2"-->
<!--            >Настройки</button>-->

            <searchable-select
                class="inline md:ml-2 md:mt-0 mt-2"
                input-classes="select-control"
                v-model="chartGroupType"
                :options="[
                    {title: 'Группировать по дням', value: 'days-1'},
                    {title: 'Группировать по 3 дня', value: 'days-3'},
                    {title: 'Группировать по 30 дней', value: 'days-30'},
                    {title: 'Группировать по неделям', value: 'weeks'},
                    {title: 'Группировать по месяцам', value: 'months'},
                ]"
            />
        </div>

        <analytics-chart :campaign="campaign"
                         :date-range="dateRangeProxy"
                         :comparing-date-range="comparingDateRangeProxy"
                         :field="selectedField"
                         :paths="selectedObjects"
                         :comparing="isInComparing"
                         :group-type="chartGroupType"
                         class="w-full py-4"
        />

        <div class="mt-4 overflow-x-auto pb-4">
            <h2 class="font-black text-xl mb-4">Источники</h2>
            <tree-table :date-range="dateRangeProxy"
                        :comparing-date-range="comparingDateRangeProxy"
                        :comparing="isInComparing"
                        :campaign="campaign"
                        v-model:field-for-chart="selectedField"
                        v-model:items-for-chart="selectedObjects"
                        class="w-full"
                        @open-filters="showSettingsModal = true"
                        :fields-settings="tableFieldsSettings"
            />
        </div>

        <modal :show="showSettingsModal" max-width="sm" @close="showSettingsModal = false">
            <settings-modal :campaign-id="campaign" @saved="onSettingsSaved" />
        </modal>
    </div>
</template>

<script setup>
import TreeTable from "@/Pages/Campaign/Analytics/Components/TreeTable.vue";
import PeriodControls from "@/Components/Forms/PeriodControls.vue";
import {isEmpty, parseParamsFromUrl} from "@/utils";
import {computed, onBeforeMount, ref, watch} from "vue";
import AnalyticsChart from "@/Pages/Campaign/Analytics/Components/AnalyticsChart.vue";
import {dateRangeToStr, getPreviousDateRange, dateRangeFormat} from "@/dateRange";
import {usePage, router} from "@inertiajs/vue3";
import SettingsModal from "@/Pages/Campaign/Analytics/Components/SettingsModal.vue";
import Modal from "@/Components/Modal/Modal.vue";
import PageTitle from "@/Components/PageTitle.vue";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";

const props = defineProps({
    dateRange: {
        type: [String, Array, Object],
        required: true,
    },
    comparingDateRange: {
        type: [String, Array, Object],
        required: true,
    },
    campaign: {
        type: Object,
        required: true,
    },
    tableFieldsSettings: {
        type: [Array, Object, null],
        required: false,
        default: null,
    },
});

function onSettingsSaved() {
    showSettingsModal.value = false;
    router.reload({
        // Чтобы стриггерить watch и обновить данные
        //... возможно не работает))
        only: ['dateRange'],
    });
}

const showSettingsModal = ref(false);

const pageUrl = usePage().url;

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

const isInComparing = ref(false);
const chartGroupType = ref('days-1');

const dateRangeStr = computed(() => dateRangeFormat(dateRangeProxy.value, true));
const comparingDateRangeStr = computed(() => dateRangeFormat(comparingDateRangeProxy.value, true));

const dateRange = ref();
const dateRangeProxy = computed({
    get: () => dateRangeToStr(dateRange.value ?? props.dateRange ?? '7days'),
    set: (val) => dateRange.value = val,
});

const comparingDateRange = ref();
const comparingDateRangeProxy = computed({
    get: () => dateRangeToStr(comparingDateRange.value ?? props.comparingDateRange ?? getPreviousDateRange(dateRangeProxy.value)),
    set: (val) => comparingDateRange.value = val,
});

const selectedField = ref('expenses');
watch(selectedField, updateUrl);

const selectedObjects = ref();

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

</script>

<script>
import layout from "@/Layouts/Authenticated.vue";

export default {layout};
</script>
