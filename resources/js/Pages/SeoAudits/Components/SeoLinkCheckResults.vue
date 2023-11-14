<template>
    <div>
        <div class="py-1 cursor-pointer text-sm" @click="isDeployed = !isDeployed">
            <div v-if="seoAudits.length">Проверено: {{ seoAudits.length }}</div>
            <p class="mr-8 ">Общая оценка {{ totalScore }} из 100</p>
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
            <AdAuditItem :audit="seoAudits" :one-link="!seoAudits.length"/>
        </div>
    </div>
</template>

<script setup>
import SliderDeployButton from "@/Pages/Campaign/Checks/Components/SliderDeployButton.vue";
import AdAuditItem from "@/Pages/SeoAudits/Components/AdAuditItem.vue";
import ProgressBar from "@/Components/ProgressBar.vue";
import {ref} from "vue";

const props = defineProps({
    seoAudits: {
        type: [Array],
        required: true,
    },
    checks: {
        type: Array,
        required: false,
    },
    totalScore: {
        type: Number,
        required: true
    }
});

const isDeployed = ref(false);

// const totalScore = computed(() => {
//     return 0;
//     let totalScore = 0;
//     for (const audit of props.seoAudits) {
//         totalScore += countScore(audit.simple_result)
//     }
//     if (props.seoAudits.length > 0)
//         return r(totalScore * 100 / props.seoAudits.length)
//     else
//         return 0;
// });
//
// const countScore = (simpleResult) => {
//     let score = 0;
//     let countChecks = 0;
//     for (const simpleResultKey in simpleResult) {
//         for (const check in simpleResult[simpleResultKey]) {
//             if (simpleResult[simpleResultKey][check].score === null) {
//                 simpleResult[simpleResultKey][check].score = 1;
//             }
//
//             score += simpleResult[simpleResultKey][check].score ?? 0;
//             countChecks++;
//         }
//     }
//
//     return score / countChecks;
// }



</script>

<style scoped>

</style>
