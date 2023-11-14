<template>
    <page-title title="Аудит"/>

    <section class="mb-8">
        <h2 class="title mb-4 pl-2">
            <span>Аудит</span>
        </h2>
        <div v-show="!isLoading">

            <template v-for="(checkGroups, cabinetName) in cabinets" v-if="!isEmpty(cabinets)">
                <cabinet-checks-card
                    class="mb-4"
                    v-if="!isEmpty(checkGroups)"
                    :cabinet-name="cabinetName"
                    :check-groups="checkGroups"
                    :key="cabinetName"
                    v-model:filters="filters[cabinetName]"
                    :open="cabinetName === openSource"
                    :campaign-id="campaign.id"
                />
            </template>

            <div v-else class="text-center">
                Отсутствует рекламная кампания
            </div>
        </div>

        <div class="flex w-full justify-center py-8 items-center" v-show="isLoading">
            <loading-spinner dark/>
            <span class="ml-4">Загрузка...</span>
        </div>
    </section>

    <section class="mb-8">
        <h3 class="title mb-4 pl-2">SEO проверки</h3>
        <seo-audit-card :history="seoAuditHistory"/>
    </section>

    <section v-if="!isEmpty(sourcesAuditSources)">
        <h2 class="title mb-4 pl-2">Проверка ссылок</h2>
        <ad-audit-card
            v-for="source in sourcesAuditSources"
            :item="source"
        />
    </section>
</template>

<script setup>
import CabinetChecksCard from "@/Pages/Campaign/Checks/Components/CabinetChecksCard.vue";
import {isEmpty, ls} from "@/utils";
import {computed, onBeforeMount, ref, watch} from "vue";
import LoadingSpinner from "@/Components/LoadingSpinner.vue";
import PageTitle from "@/Components/PageTitle.vue";
import AdAuditCard from "@/Pages/SeoAudits/Components/AdAuditCard.vue";
import SeoAuditCard from "@/Pages/SeoAudits/Components/SeoAuditCard.vue";

const props = defineProps({
    campaign: {
        type: Object,
        required: true,
    },
    openSource: {
        type: [String, null],
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
});

const filters = ref({});
const cabinets = ref({});
const isLoading = ref(true);

watch(filters, () => {
    ls.setJ(lsKey.value, filters.value);

    // При изменении фильтров показывать индикатор не надо
    // isLoading.value = true;
    axios.post(route('campaign.checks.load', props.campaign.id), {filters: filters.value}).then((res) => {
        cabinets.value = res.data;
        // console.log(cabinets.value);
        isLoading.value = false;
    });
}, {deep: true});

onBeforeMount(async () => {
    filters.value = ls.getJ(lsKey.value) ?? {};
});

const lsKey = computed(() => `audit|filters|${props.campaign.id}`);
</script>

<script>
import layout from "@/Layouts/Authenticated.vue";

export default {layout};
</script>
