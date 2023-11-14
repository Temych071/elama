<template>
    <div class="text-sm">
        <div v-if="!oneLink" class="flex items-center">
            <a
                :href="audit['link']"
                target="_blank"
                class="font-bold"
            >
                [{{ audit['document_status_code'] }}] {{ audit['link'] }}
            </a>
            <SliderDeployButton v-model:is-deployed="isAdsDeployed"/>
        </div>
        <div class="my-2">
            Пройдено проверок {{ totalSucceededChecks }} из {{ totalChecks }}
        </div>
<!--        <div class="flex my-2">-->
<!--            <div class="mr-4">SEO: {{ r(audit['seo_score'] * 100) }}%</div>-->
<!--            <div class="mr-4">Производительность: {{ r(audit['performance_score'] * 100) }}%</div>-->
<!--            <div class="mr-4">Лучшие практики: {{ r(audit['best_practices_score'] * 100) }}%</div>-->
<!--        </div>-->
<!--        <div class="text-error my-2">-->
<!--            <div v-if="!audit['has_metrika']">&nbsp;-&nbsp;На странице не установлен счетчик Яндекс.Метрики</div>-->
<!--            <div v-if="!audit['has_vk_pixel']">&nbsp;-&nbsp;На странице не установлен пиксель ВКонтакте</div>-->
<!--        </div>-->
<!--        <div>-->
<!--            <a :href="route('seo-audit.show', audit['uuid'])" target="_blank" class="text-primary">Смотреть полный отчет</a>-->
<!--        </div>-->

        <div v-if="isAdsDeployed" class="my-2">
            <audit-group :group="groupTechnicalCondition" name="Техническое состояние"/>
            <audit-group :group="groupWebsiteOptimization" name="Оптимизация сайта"/>
            <audit-group :group="groupLoadingSpeed" name="Скорость загрузки сайта"/>
        </div>

    </div>
<!--    <div class="flex justify-start py-1 cursor-pointer">-->
<!--        <span @click="isAdsDeployed = !isAdsDeployed">Используется в {{ links.length }} объявлениях</span>-->
<!--        <SliderDeployButton v-model:is-deployed="isAdsDeployed"/>-->
<!--    </div>-->
<!--    <div v-if="isAdsDeployed" class="pl-4 mb-2">-->
<!--        <div v-for="link in links">-->
<!--            <a :href="link['cabinet_link']" class="hover:text-primary my-1">{{ link['title'] }}</a>-->
<!--        </div>-->
<!--    </div>-->
</template>

<script setup>

import {computed, ref} from "vue";
import {isEmpty, isEmpty2} from "@/utils";
import AuditGroup from "@/Pages/Campaign/Checks/Components/AuditItems/AuditGroup.vue";
import SliderDeployButton from "@/Pages/Campaign/Checks/Components/SliderDeployButton.vue";

const props = defineProps({
    audit: {
        type: [Array, Object]
    },
    checks: Array,
    oneLink: {
        type: Boolean,
        default: false,
    }
});

const isAdsDeployed = ref(props.oneLink);

const simpleResult = computed(() => {
    // console.log(props.audit)
    // if (Array.isArray(props.audit))
    //     return

    return props.audit.simple_result;
});

const links = computed(() => {
    return props.checks.find(i => i['url'] === props.audit['link'])['ads']
});

const HIDDEN_CHECKS = ['keywords'];

const groupCategory = (category) => {
    let succeeded = [];
    let failed = [];

    for (const field in category) {
        if (HIDDEN_CHECKS.includes(field)) {
            continue;
        }

        if(isEmpty(category[field].score)) {
            category[field].score = 0;
        }

        category[field].__key = field;

        if (isEmpty(category[field]) || category[field].score < .5 || category[field] === false) {
            failed.push(category[field])
            continue;
        }

        succeeded.push(category[field])
    }

    return {
        'succeeded': succeeded,
        'failed': failed,
    }
};

const groupTechnicalCondition = computed(() => {
    let data = {...simpleResult.value.technical_condition};

    if (!isEmpty2(props.audit.internal_links)) {
        data.internal_links = props.audit.internal_links;
    }

    return groupCategory(data);
});
const groupLoadingSpeed = computed(() => {
    return groupCategory(simpleResult.value.loading_speed);
});
const groupWebsiteOptimization = computed(() => {
    return groupCategory(simpleResult.value.website_optimization);
});

const totalSucceededChecks = computed(() => {
    let count = groupTechnicalCondition.value.succeeded.length;
    count += groupLoadingSpeed.value.succeeded.length;
    count += groupWebsiteOptimization.value.succeeded.length;
    return count;
});

const totalChecks = computed(() => {
    let count = 0;
    for (const categoryKey in simpleResult.value) {
        for (const checkKey in simpleResult.value[categoryKey]) {
            count++;
        }
    }
    return count;
});

</script>
