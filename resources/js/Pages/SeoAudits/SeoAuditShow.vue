<template>
    <Head>
        <title>{{ audit.link }} - SEO-проверка</title>
    </Head>

    <Breadcrumb>
        <BreadcrumbItem :href="route('seo-audit.index')">SEO</BreadcrumbItem>
        <BreadcrumbItem>{{ audit.link }}</BreadcrumbItem>
    </Breadcrumb>

    <div class="flex justify-between">
        <div class="mb-4" v-if="isWaiting">Идет проверка...</div>
        <div v-if="audit.data_updated_at" class="mb-4 text-sm">Проверка была произведена
            {{ dateToUserTzString(audit.data_updated_at) }}
        </div>
    </div>

    <div v-if="audit.result?.lighthouseResult">
        <div v-if="audit.result?.lighthouseResult?.runWarnings?.length || audit['has_metrika'] || audit['has_vk_pixel']">
            <h2 class="title mb-2">Предупреждения</h2>
            <ul class="list-disc pl-4">
                <li v-for="item in audit.result.lighthouseResult.runWarnings" class="mb-1">{{ item }}</li>
                <li v-if="!audit['has_metrika']" class="mb-1">На странице не установлен счетчик Яндекс.Метрики</li>
                <li v-if="!audit['has_vk_pixel']" class="mb-1">На странице не установлен пиксель ВКонтакте</li>
            </ul>
        </div>

        <section class="mt-10 mb-20">
            <h2 class="title">Производительность сайта</h2>
            <div class="grid grid-cols-3 gap-6 gap-y-12 mt-4">
                <MetricCard
                    v-for="(metric, key) in audit.result.loadingExperience.metrics"
                    :title="key"
                    :item="metric"
                />
            </div>
        </section>

        <h2 class="title">Проверки</h2>
        <section v-for="category in audit.result.lighthouseResult.categories" class="my-8">
            <AuditCategoryItem
                :key="category.id"
                :category="category"
                :audits="audit.result.lighthouseResult.audits"
            />
        </section>
    </div>
</template>

<script setup>

import {computed, onMounted, onUnmounted} from "vue";
import {router} from "@inertiajs/vue3"
import {dateToUserTzString} from "@/utils";
import MetricCard from "@/Pages/SeoAudits/Components/MetricCard.vue";
import Breadcrumb from "@/Components/Breadcrumbs/Breadcrumb.vue";
import BreadcrumbItem from "@/Components/Breadcrumbs/BreadcrumbItem.vue";
import AuditCategoryItem from "@/Pages/SeoAudits/Components/AuditCategoryItem.vue";

const props = defineProps({
    audit: {
        type: Object,
    },
});

let intervalId;

onMounted(() => refresh());

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId);
    }
});

const isWaiting = computed(() => props.audit.status === 'wait');

function refresh() {
    if (!isWaiting.value) {
        return;
    }

    intervalId = setTimeout(() => {
        router.reload({
            onSuccess() {
                if (isWaiting.value) {
                    refresh();
                }
            }
        });
    }, 5000);
}

</script>

<script>
import layout from "@/Layouts/Authenticated.vue";

export default {layout};
</script>
