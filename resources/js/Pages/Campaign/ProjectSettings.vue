<template>
    <page-title :lang="$t('campaigns.settings.project')"/>

    <div class="md:w-5/6 w-full">
        <PlanFactSettings :campaign="campaign" :plans="plans"/>

        <hr class="mt-2 mb-10"/>

        <SettingsSelector
            title="Графики План-факт"
            :available-settings="availableFields"
            :selected-settings="form.order"
            @selected="onPlanFactSettingsSelected"
            class="max-w-xl"
        />

        <hr class="mt-12 mb-10"/>

        <SettingsSelector
            title="Виджеты"
            :available-settings="availableNotifications"
            :selected-settings="selectedNotifications"
            @selected="onNotificationsSelected"
            :show="!emptyAnalyticsSources"
            reason-not-showing="Яндекс Метрика или Google Analytics не подключены"
            :route-to="routeSource"
            class="max-w-xl"
        />

        <hr class="mt-12 mb-10"/>

        <SettingsSelector
            title="Показатели аналитики"
            :available-settings="availableAnalyticsParameters"
            :selected-settings="selectedAnalyticsParameters"
            @selected="onAnalyticsParametersSelected"
            class="max-w-xl"
        />

        <hr class="mt-12 mb-10"/>

        <h2 class="settings-title mb-6">Аудит</h2>
        <checkbox
            @update:checked="onUpdateShowLinkChecker"
            :checked="settingsChecks?.showLinkChecker ?? false"
        >Отображение блока проверки ссылок
        </checkbox>
        <checkbox
            @update:checked="onUpdateShowSeoAudit"
            :checked="settingsChecks?.showSeoAudit ?? false"
        >Отображение блока SEO-проверок
        </checkbox>
    </div>
</template>

<script setup>
import SettingsSelector from "@/Components/Forms/SettingsSelector.vue";
import PlanFactSettings from "@/Components/Forms/PlanFactSettings.vue"
import {computed, onBeforeMount, ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import {useI18n} from 'vue-i18n'
import PageTitle from "@/Components/PageTitle.vue";
import Checkbox from "@/Components/Checkbox.vue";

const props = defineProps({
    plans: {
        type: Array,
        required: true,
    },
    months: {
        type: Array,
        required: true,
    },
    campaign: {
        type: Object,
        required: true,
    },
    notificationTypes: {
        type: Object,
        required: true,
    },
    emptyAnalyticsSources: {
        type: Boolean,
        required: true,
    },
    settingsChecks: {
        type: Object,
        required: true,
    },
});

function onUpdateShowSeoAudit(newValue) {
    router.put(
        route('campaign.project_settings.store_checks', props.campaign.id),
        {showSeoAudit: newValue},
        {preserveScroll: true},
    );
}

function onUpdateShowLinkChecker(newValue) {
    router.put(
        route('campaign.project_settings.store_checks', props.campaign.id),
        {showLinkChecker: newValue},
        {preserveScroll: true},
    );
}

const {t} = useI18n();

const analyticsParameters = ['expenses', 'income', 'clicks', 'requests', 'cpc', 'cpl', 'cr', 'drr', 'purchases', 'impressions', 'ctr'];

const routeSource = computed(() => route('campaign.source', props.campaign.id));

const getSelectedNotifications = computed(() => props.campaign.notifications?.map(
    (key) => ({
        name: props.notificationTypes[key],
        id: key
    }),
));

const getSelectedAnalyticsParameters = computed(() => props.campaign.analytics_parameters?.map(
    (key) => ({
        name: t('campaigns.analytics.parameters.' + key),
        id: key,
    }),
));

const filterAvailableAnalyticsParams = computed(() => {
    let available = analyticsParameters.map(
        (key) => ({
            name: t('campaigns.analytics.parameters.' + key),
            id: key,
        }),
    );
    return available.filter(value => !getSelectedAnalyticsParameters.value?.map(value => value.id).includes(value.id));
});

const filterAvailableNotifications = computed(() => {
    let available = Object.keys(props.notificationTypes).map(
        (key) => ({
            name: props.notificationTypes[key],
            id: key
        })
    );
    return available.filter(value => !getSelectedNotifications.value?.map((value) => value.id).includes(value.id))
});

const onNotificationsSelected = (settingsList) => {
    formNotifications.value = settingsList.map((value) => value.id);
    formNotifications.put(route('campaign.project_settings.store_notifications', props.campaign.id), {preserveScroll: true})
}

const onPlanFactSettingsSelected = (settingsList) => {
    form.order = settingsList.map((value, index) => {
        return {
            num: index + 1,
            field: value.key,
        }
    });

    form.put(route('campaign.planfact.order.store', props.campaign.id), {preserveScroll: true});
};

const onAnalyticsParametersSelected = (paramsList) => {
    formAnalytics.order = paramsList.map(value => value.id);
    formAnalytics.put(
        route('campaign.project_settings.store_analytics', props.campaign.id),
        {preserveScroll: true}
    );
}

const selectedNotifications = ref(getSelectedNotifications.value);
const availableNotifications = ref(filterAvailableNotifications.value);

const selectedAnalyticsParameters = ref(getSelectedAnalyticsParameters.value);
const availableAnalyticsParameters = ref(filterAvailableAnalyticsParams.value);

const fields = ['expenses', 'income', 'clicks', 'requests', 'cpc', 'cpl', 'cr', 'drr'];
const availableFields = ref(fields.map((value, index) => ({num: index + 1, field: value})));

const form = useForm({
    order: [],
});
const formNotifications = useForm({
    value: []
});
const formAnalytics = useForm({
    order: [],
})

const values = computed(() => props.campaign.planfact_order);

onBeforeMount(() => {
    form.order = values.value.sort((a, b) => {
        if (a.num < b.num) {
            return -1;
        }
        return a.num > b.num;
    });
    if (!form.order.length) {
        form.order = fields.map((field, num) => {
            return {num: num + 1, field};
        });
    }

    availableFields.value = availableFields.value.filter(value => !form.order.map(
        (value) => value.field).includes(value.field));
    availableFields.value = availableFields.value.map((value) => ({
        name: t('campaigns.planfact.fields.' + value.field),
        id: value.num,
        key: value.field,
    }));
    form.order = form.order.map((value) => ({
        name: t('campaigns.planfact.fields.' + value.field),
        id: value.num,
        key: value.field,
    }));

    formNotifications.value = selectedNotifications;
});


</script>

<script>
import layout from "@/Layouts/Authenticated.vue";

export default {layout};
</script>

<style scoped lang="scss">
.settings-title {
    font-weight: 900;
    font-size: 16px;
    line-height: 20px;
    letter-spacing: 0.2px;
    color: #252733;
}
</style>
