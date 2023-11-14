<template>
    <div
        class="grid lg:grid-cols-5 md:grid-cols-3 sm:grid-cols-2 gap-2 mb-8"
        v-if="activeSourceType !== null && hasConv"
    >
        <template v-for="item in feedCardsList">
            <CardNotification v-if="item.type ==='audit'">
                <template #title>
                    <Link
                        :href="route('campaign.checks', {campaign: campaignId})"
                        class="text-[14px] font-normal hover:underline"
                    >
                        {{ $t(item.langKey + '.title') }}
                    </Link>
                </template>

                <div v-if="!isEmpty(audit)">
                    <Link
                        v-for="(stats, name) in audit"
                        class="flex flex-row justify-between mb-1 rounded-sm hover:underline"
                        style="text-decoration-color: #9E9E9E"
                        :href="route('campaign.checks', {campaign: campaignId, source: name})"
                    >
                        <span class="font-normal text-xs text-[#6C757D] mt-1">
                            {{ $t('sources.names.' + name) }}
                        </span>
                        <span
                            class="ml-2 text-[10px] font-bold px-1.5"
                            :class="{up: stats >= .9, middle: stats >= .5 && stats < .9, down: stats < .5}"
                        >
                            {{ r(stats * 100, 0) }}%
                        </span>
                    </Link>
                </div>
                <div v-else>
                    -
                </div>
            </CardNotification>

            <div v-if="hasConvField(item.field)">
                <CardNotification v-if="item.type === 'cities'">
                    <template #title>
                            <span class="text-[14px] font-normal">
                                {{ $t(item.langKey + '.title') }}
                            </span>
                    </template>

                    <div
                        v-for="(el, i) in selConv[item.fieldName]"
                        class="font-normal text-xs text-[#6C757D] mt-1 leading-none"
                        v-if="selConv[item.fieldName]"
                    >
                        <div v-if="i < 3">
                            {{ el.city }} ({{ item.valuesCallback(r(el[item.field])) }})
                        </div>
                    </div>

                    <div v-else class="text-xl font-medium text-gray-700">
                        -
                    </div>
                </CardNotification>

                <CardNotification v-else-if="item.type === 'conversion'">
                    <template #title>
                        <span class="text-[14px] font-normal">
                            {{ $t(item.langKey + '.title') }}
                        </span>
                    </template>

                    <div class="flex flex-row">
                        <span class="font-bold text-base leading-5">
                            {{ item.valuesCallback(round(selConv[item.field], item.field)) }}
                        </span>
                        <div
                            class="ml-2 text-[10px] font-bold px-[6px]"
                            :class="colorPercent(item)"
                        >
                            {{ separateNumber(diffConv(item.field)) }}%
                        </div>
                    </div>

                    <template #footer>
                        <p class="mt-2" v-html="$t('campaigns.feed.footerNotification', {
                            prePeriod: prePeriodFmt,
                            selPeriod: selPeriodFmt,
                            preConv: item.valuesCallback(round(preConv[item.field], item.field)),
                            selConv: item.valuesCallback(round(selConv[item.field], item.field)),
                        })"></p>
                    </template>
                </CardNotification>
            </div>
        </template>
    </div>
</template>

<script setup>
import CardNotification from "@/Pages/Campaign/Components/CardNotification.vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import {separateNumber, r, periodFormat, formatTimeLength, isEmpty, diffPercents, n} from "@/utils";
import {computed, onMounted, ref, watch} from "vue";

// TODO: ЭТО ВСЁ НАДО ПЕРЕПИСЫВАТЬ)))

const props = defineProps({
    campaignNotifications: {
        type: [Array, null],
        required: true,
    },
    campaignId: {required: true,},
    activeSourceType: String,
    dateRange: {required: true},
});

const fields = {
    city_reaches: {
        type: 'cities',
        field: 'reaches',
        units: '',
        langKey: 'campaigns.feed.city_reaches',
        arrowReverse: false,
        category: 'geo',
        fieldName: 'city_reaches',
        valuesCallback: separateNumber,
    },
    // city_conversion_rate: {
    //     type: 'cities',
    //     field: 'conversion_rate',
    //     units: '%',
    //     langKey: 'campaigns.feed.city_conversion_rate',
    //     arrowReverse: false,
    //     category: 'geo',
    // },
    city_new_users: {
        type: 'cities',
        field: 'new_users',
        units: '',
        langKey: 'campaigns.feed.city_new_users',
        arrowReverse: false,
        category: 'geo',
        fieldName: 'city_new_users',
        valuesCallback: separateNumber,
    },
    audit: {
        type: 'audit',
        langKey: 'campaigns.feed.audit',
        arrowReverse: false,
        category: 'behavior',
        field: 'audit',
        valuesCallback: separateNumber,
    },
    new_users: {
        type: 'conversion',
        langKey: 'campaigns.feed.new_users',
        arrowReverse: false,
        category: 'users',
        field: 'new_users',
        valuesCallback: separateNumber,
    },
    avg_visit_duration: {
        type: 'conversion',
        langKey: 'campaigns.feed.avg_visit_duration',
        arrowReverse: false,
        category: 'behavior',
        field: 'avg_visit_duration',
        valuesCallback: formatTimeLength,
    },
    // visits: {
    page_views: {
        type: 'conversion',
        langKey: 'campaigns.feed.visits',
        arrowReverse: false,
        category: 'users',
        field: 'visits',
        valuesCallback: separateNumber,
    },
    // page_views: {
    //     type: 'conversion',
    //     langKey: 'campaigns.feed.page_views',
    //     arrowReverse: false,
    //     category: 'users',
    //     field: 'page_views',
    //     valuesCallback: separateNumber,
    // },
    bounce_rate: {
        type: 'conversion',
        langKey: 'campaigns.feed.bounce_rate',
        arrowReverse: true,
        category: 'behavior',
        field: 'bounce_rate',
        valuesCallback: separateNumber,
    },
    depth: {
        type: 'conversion',
        langKey: 'campaigns.feed.depth',
        arrowReverse: false,
        category: 'behavior',
        field: 'depth',
        valuesCallback: separateNumber,
    },
    reaches: {
        type: 'conversion',
        langKey: 'campaigns.feed.reaches',
        arrowReverse: false,
        category: 'behavior',
        field: 'reaches',
        valuesCallback: separateNumber,
    },
    conversion_rate: {
        type: 'conversion',
        langKey: 'campaigns.feed.conversion_rate',
        arrowReverse: false,
        category: 'behavior',
        field: 'conversion_rate',
        valuesCallback: (val) => `${separateNumber(val)}%`,
    },
    mobile_traffic: {
        type: 'conversion',
        langKey: 'campaigns.feed.mobile_traffic',
        arrowReverse: false,
        category: 'users',
        field: 'mobile_traffic',
        valuesCallback: separateNumber,
    },
    visitors: {
        type: 'conversion',
        langKey: 'campaigns.feed.visitors',
        arrowReverse: false,
        category: 'users',
        field: 'visitors',
        valuesCallback: separateNumber,
    },
    purchases: {
        type: 'conversion',
        langKey: 'campaigns.feed.purchases',
        arrowReverse: false,
        category: 'ecommerce',
        field: 'purchases',
        valuesCallback: separateNumber,
    },
    income: {
        type: 'conversion',
        langKey: 'campaigns.feed.income',
        arrowReverse: false,
        category: 'ecommerce',
        field: 'income',
        valuesCallback: separateNumber,
    },
    // devices: {
    //     type: 'devices',
    //     langKey: 'campaigns.feed.devices',
    //     arrowReverse: false,
    //     category: 'users',
    // },
}
const audit = ref(null);

onMounted(async () => {
    if (props.campaignNotifications?.includes('audit')) {
        audit.value = (await axios.get(route('campaign.browse.audit', props.campaignId))).data;
    }
});

const colorPercent = (item) => {
    if (item.arrowReverse) {
        return {
            up: !upConv(item.field),
            down: upConv(item.field)
        }
    }

    return {
        up: upConv(item.field),
        down: !upConv(item.field)
    }
}

const availableFields = computed(() => {
    if (isEmpty(props.campaignNotifications)) {
        return {};
    }

    const availableKeys = [];
    const fieldsKeys = Object.keys(fields);
    for (const notification of props.campaignNotifications) {
        if (fieldsKeys.includes(notification)) {
            availableKeys.push(notification);
        }
    }

    return availableKeys.map((key) => fields[key]);
});

const feedCardsList = computed(() => {
    if (props.activeSourceType === null) {
        return [];
    }
    return availableFields.value;
});

const round = (value, name) => {

    if (name === 'bounce_rate') {
        return r(value, 2);
    }
    if (name === 'depth') {
        return r(value, 2);
    }
    return r(value, 4);
}

const convertPeriod = (period) => {
    return {
        from: new Date(period.from),
        to: new Date(period.to),
    }
}

const diffConv = (field) => {
    return diffPercents(
        n(preConv.value[field]),
        n(selConv.value[field]),
    );
};

const upConv = (field) => {
    return n(selConv.value[field]) > n(preConv.value[field]);
}

const prePeriod = computed(() => {
    return convertPeriod(usePage().props.prevPeriod);
})

const prePeriodFmt = computed(() => {
    return periodFormat(prePeriod.value);
})

const hasConv = computed(() => {
    return (
        usePage().props.prevConversions
        && usePage().props.conversions
    );
});

const selConv = computed(() => {
    return usePage().props.conversions;
});

const preConv = computed(() => {
    return usePage().props.prevConversions;
})

const hasConvField = (field) => {
    return (
        selConv.value[field] !== null
        && selConv.value[field] !== undefined
    );
}

const selPeriod = computed(() => {
    return convertPeriod(usePage().props.dateRange);
});

const selPeriodFmt = computed(() => {
    return periodFormat(selPeriod.value);
});

watch(() => props.dateRange, () => {
    reloadData();
});

function reloadData() {
    router.reload({
        only: [
            'conversions',
            'period',
            'dateRange',
            'prevConversions',
            'periodLength',
        ],
    });
}

</script>

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
</style>
