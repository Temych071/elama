<template>
    <div class="flex justify-between mb-2">
        <h2>{{ category['title'] }}</h2>
        <div :class="{
            'text-success-dark': category['score'] >= .9,
            'text-warning': category['score'] >= .5 && category['score'] < .9,
            'text-error': category['score'] !== null && category['score'] >= 0 && category['score'] < .5,
        }"
        >Общая оценка - {{ category['score'] }}
        </div>
    </div>

    <template v-for="auditItem in auditList['errors']">
        <AuditCard :item="auditItem"/>
    </template>

    <template v-if="auditList['success']">
        <div class="success-collapse" @click="isSuccessCollapsed = !isSuccessCollapsed">
            <span>Успешные проверки ({{ auditList['success'].length }})</span>
            <span>{{ isSuccessCollapsed ? 'Показать' : 'Скрыть' }}</span>
        </div>

        <AuditCard v-if="!isSuccessCollapsed"
                   v-for="auditItem in auditList['success']"
                   :item="auditItem"/>
    </template>
</template>

<script setup>

import {computed, ref} from "vue";
import AuditCard from "@/Pages/SeoAudits/Components/AuditCard.vue";

const props = defineProps({
    category: Object,
    audits: Array,
});

const isSuccessCollapsed = ref(true);

const auditList = computed(() => {
    return _.chain(props.category['auditRefs'])
        .map((auditRef) => props.audits[auditRef.id] ?? false)
        .filter()
        .sortBy('score')
        .groupBy(item => +item['score'] >= .9 ? 'success' : 'errors')
        .value();
});


</script>

<style scoped lang="scss">
.success-collapse {
    margin-top: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 16px;
    font-size: 15px;
    border-radius: 4px;
    cursor: pointer;
    border-bottom: 1px solid #ececec;
    color: #363636;

    &:hover {
        background: #f3f3f3;
    }
}
</style>
