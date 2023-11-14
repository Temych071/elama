<template>
    <Card class="mb-4">
        <template #time>
            <div v-if="item.status === 'completed'">{{ lastUpdateDate }}</div>
            <div v-else-if="item.status === 'waiting'">Загрузка..</div>
        </template>

        <template #title>
            {{ $t('sources.names.' + item.source.settings_type) }}
        </template>

        <div v-if="item['seo_audits']">
            <div class="py-1 cursor-pointer" @click="isDeployed = !isDeployed">
                <div>Проверено: {{ item['seo_audits']?.length }}</div>
                <p class="mr-8">Общая оценка {{ totalScore }} из 100</p>
                <div class="flex items-center mt-4">
                    <progress-bar
                        :total="100"
                        :value="totalScore"
                        :color-by-value="true"
                        class="flex-grow rounded-md cursor-pointer mr-2"
                        @click="isDeployed = !isDeployed"
                    />
                    <SliderDeployButton v-model:is-deployed="isDeployed"/>
                </div>
            </div>
            <div v-if="isDeployed" class="px-4 py-2">
                <div v-for="audit in item['seo_audits']" class="py-4 seo-item">
                    <AdAuditItem :audit="audit" :checks="item['checks']" />
                </div>
            </div>
        </div>
    </Card>
</template>

<script setup>
import Card from "@/Components/MetrikaSummary/Feed/Card.vue";
import {computed, ref} from "vue";
import {dateToUserTzString, r} from "@/utils";
import SliderDeployButton from "@/Pages/Campaign/Checks/Components/SliderDeployButton.vue";
import AdAuditItem from "@/Pages/SeoAudits/Components/AdAuditItem.vue";
import ProgressBar from "@/Components/ProgressBar.vue";

const props = defineProps({
    item: Object,
});

const isDeployed = ref(false);

const seoAudits = computed(() => {
   return props.item.seo_audits
});
const totalScore = computed(() => {
    let totalScore = 0;
    for (const audit of seoAudits.value) {
        totalScore += countScore(audit.simple_result)
    }
    if (seoAudits.value.length > 0)
        return r(totalScore * 100 / seoAudits.value.length)
    else
        return 0;
});

const countScore = (simpleResult) => {
    let score = 0;
    let countChecks = 0;
    for (const simpleResultKey in simpleResult) {
        for (const check in simpleResult[simpleResultKey]) {
            if (simpleResult[simpleResultKey][check].score === null) {
                simpleResult[simpleResultKey][check].score = 1;
            }

            score += simpleResult[simpleResultKey][check].score ?? 0;
            countChecks++;
        }
    }

    return score / countChecks;
}

const lastUpdateDate = computed(() => {
    if (props.item['status'] === 'waiting') {
        return 'Идет обновление';
    }

    const updatedAt = props.item['updated_at'] ?? props.item['created_at'] ?? null;

    return updatedAt ? dateToUserTzString(updatedAt) : '';
});

const seoScore = computed(() => _.meanBy(props.item['seo_audits'], 'seo_score'))
const performanceScore = computed(() => _.meanBy(props.item['seo_audits'], 'performance_score'))
const bestPracticesScore = computed(() => _.meanBy(props.item['seo_audits'], 'best_practices_score'))

</script>

<style scoped lang="scss">
.seo-item + .seo-item {
    border-top: 1px solid rgba(0, 0, 0, .1);
    margin-top: -8px;
}
</style>
