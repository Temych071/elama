<template>
    <div class="flex flex-col border border-gray-200 mr-6 my-4 p-4">
        <div class="mb-4 flex-shrink-0">
            <h3>
                <span class="text-lg font-bold">{{ plan.name }}</span>
                <span class="text-xs text-gray-800 ml-1" v-if="plan.status === 'archived'">(Архивный)</span>
            </h3>
            <p class="text-sm" v-if="plan.visits_limit">
                до {{ separateNumber(plan.visits_limit) }} визитов в месяц
            </p>
            <p class="text-sm" v-else>Без ограничения по визитам</p>
        </div>

        <div v-if="!isEmpty(plan.description)" v-html="plan.description"></div>

        <div class="flex-grow"></div>
        <div class="h-20"></div>

        <div>
            <p class="text-2xl">{{ separateNumber(plan.formatted_price) }} ₽/мес</p>
            <p class="text-sm">1 проект</p>
        </div>

        <div class="card-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script setup>
import {r, isEmpty, separateNumber} from "@/utils";

const props = defineProps({
    plan: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped>

</style>
