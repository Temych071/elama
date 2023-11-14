<template>
    <card>
        <template #time>
            <span v-if="false">10:50 6 августа 2021</span>
        </template>

        <template #title>
            {{ $t('sources.names.' + cabinetName) }}
        </template>

        <div class="flex flex-wrap items-end justify-start">
            <p class="flex-shrink-0 justify-self-start mr-8">Общая оценка {{ percents }} из 100</p>
            <component v-if="!isEmpty(filtersComponent)"
                       :is="filtersComponent"
                       v-model="filterValues"
                       class="flex-shrink-0"
            />
            <div class="flex-grow flex justify-end">
                <button class="btn btn-md btn-primary ml-4" v-if="false">Обновить</button>
                <a
                   class="btn btn-md btn-outline-primary ml-2 mt-1"
                   :href="route('campaign.checks.export_source', {campaign: campaignId, source: cabinetName})"
                >Скачать отчёт</a>
            </div>
        </div>

        <template v-if="score.total > 0">
            <div class="flex flex-row items-center mt-4">
                <progress-bar :total="score.total"
                              :value="score.total - score.failed"
                              :color-by-value="true"
                              class="flex-grow rounded-md cursor-pointer"
                              @click="isDeployed = !isDeployed"
                />
                <slider-deploy-button class="ml-2" v-model:is-deployed="isDeployed"/>
            </div>

            <div v-show="isDeployed" class="mt-4">
                <template v-for="(groupChecks, groupName) in checkGroups">
                    <checks-group
                        v-if="!isEmpty(groupChecks)"
                        :group-checks="groupChecks"
                        :group-name="groupName"
                    />
                </template>
            </div>
        </template>
        <div v-else class="py-4 text-center">
            Объявления для проверки не найдены.
        </div>

    </card>
</template>

<script setup>
import Card from "@/Components/MetrikaSummary/Feed/Card.vue";
import {computed, ref} from "vue";
import ProgressBar from "@/Components/ProgressBar.vue";
import SliderDeployButton from "@/Pages/Campaign/Checks/Components/SliderDeployButton.vue";
import {isEmpty, r} from '@/utils';
import ChecksGroup from "@/Pages/Campaign/Checks/Components/ChecksGroup.vue";
import YandexDirectFilters from "@/Pages/Campaign/Checks/Components/Filters/YandexDirectFilters.vue";
import VkFilters from "@/Pages/Campaign/Checks/Components/Filters/VkFilters.vue";

const props = defineProps({
    checkGroups: {
        type: Object,
        required: true,
    },
    cabinetName: {
        type: String,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
    open: {
        type: Boolean,
        default: false,
    },
    campaignId: {
        type: Number,
        required: true,
    }
});

const emit = defineEmits(['update:filters']);

const isDeployed = ref(props.open);

const filterValues = computed({
    set: (val) => emit('update:filters', val),
    get: () => props.filters ?? {},
});

const calcChecksGroupScore = (checksGroup) => {
    let res = {
        total: 0,
        failed: 0,
    };

    for (let i in checksGroup) {
        res.total += checksGroup[i].totalObjectsCount;
        res.failed += checksGroup[i].failedObjects.length;
    }

    return res;
}

const calcChecksGroupsScore = (checksGroups) => {
    let res = {
        total: 0,
        failed: 0,
    };

    for (let groupName in checksGroups) {
        let groupScore = calcChecksGroupScore(checksGroups[groupName]);
        res.total += groupScore.total;
        res.failed += groupScore.failed
    }

    return res;
}

const score = computed(() => calcChecksGroupsScore(props.checkGroups));

const percents = computed(() => {
    if (!score.value.total) {
        return 0;
    }

    return r(((score.value.total - score.value.failed) / score.value.total) * 100, 0);
});

const filtersComponent = computed(() => {
    switch (props.cabinetName) {
        case 'yandex-direct':
            return YandexDirectFilters;
        case 'vk':
            return VkFilters;
        default:
            return null;
    }
});
</script>
