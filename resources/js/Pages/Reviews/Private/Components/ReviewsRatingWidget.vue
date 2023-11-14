<template>
    <div class="card px-6 flex flex-col gap-y-4 min-h-[450px]">
        <loading-block spinner-dark v-if="data === null" class="items-center"/>
        <template v-else>
            <div class="flex items-center gap-2">
                <span class="text-5xl">{{ r(data.totalRating, 2) }}</span>
                <stars-input readonly :model-value="4" star-class="w-8"/>
            </div>

            <div class="flex flex-wrap gap-3 text-gray-400">
                <div class="flex">
                    <span>Отзывов: </span>
                    <span><compared-value
                        :value="data.reviewsCount"
                        :cmp-value="compareData?.reviewsCount"
                        calc-func="sub"
                    /></span>
                </div>
                <div class="flex">
                    <span>Оценок: </span>
                    <span><compared-value
                        :value="data.marksCount"
                        :cmp-value="compareData?.marksCount"
                        calc-func="sub"
                    /></span>
                </div>
            </div>
            <div class="flex flex-col gap-y-1">
                <div v-for="i in [5, 4, 3, 2, 1]" class="flex flex-row items-center">
                    <span class="text-center px-2 text-lg flex items-center gap-1">{{ i }} <star/></span>
                    <stats-value-bar
                        :total="data.marksCount + data.reviewsCount"
                        :value="data.stars[i]"
                        class="w-full text-sm"
                    />
                </div>
            </div>
            <hr/>
            <div>
                <template v-for="sourceType in sourceTypes" :key="sourceType.type">
                    <div v-if="!isEmpty2(data.sourceRatings[sourceType.type])"
                         class="grid grid-cols-3">
                        <div class="col-span-2">
                            <div class="flex items-center gap-1">
                                <img alt="logo" :src="sourceType.icon"/>
                                <span>{{ sourceType.title }}</span>
                            </div>
                        </div>
                        <compared-value
                            :value="r(data.sourceRatings[sourceType.type], 2)"
                            :cmp-value="compareData ? r(compareData?.sourceRatings[sourceType.type], 2) : null"
                            calc-func="sub"
                            v-slot="{val}"
                        >
                            <div class="flex items-center gap-1">
                                <div class="w-8 text-right">{{ val }}</div>
                                <star/>
                            </div>
                        </compared-value>
                    </div>
                </template>
            </div>
            <hr/>
            <div>
                <div class="grid grid-cols-3">
                    <div class="col-span-2">Отзывы с ответами</div>
                    <compared-value
                        :value="data.answersCount"
                        :cmp-value="compareData?.answersCount"
                        calc-func="percent"
                    />
                </div>
                <div class="grid grid-cols-3">
                    <div class="col-span-2">Отзывы без ответов</div>
                    <compared-value
                        :value="data.withoutAnswerCount"
                        :cmp-value="compareData?.withoutAnswerCount"
                        calc-func="percent"
                        reverse-color
                    />
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import StarsInput from "@/Pages/Reviews/Components/StarsInput.vue";
import {isEmpty2, r} from "@/utils";
import StatsValueBar from "@/Pages/Reviews/Private/Components/StatsValueBar.vue";
import Star from "@/Pages/Reviews/Components/Star.vue";
import {onMounted, ref, watch} from "vue";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import ComparedValue from "@/Pages/Reviews/Private/Components/ComparedValue.vue";

const props = defineProps({
    projectId: {
        type: Number,
        required: true,
    },
    dateRange: {
        type: String,
        required: false,
        default: null,
    },
    sources: {
        type: Array,
        required: false,
        default: [],
    },
    reviewFormId: {
        type: Number,
        required: false,
        default: null,
    },
});

const data = ref(null);
const compareData = ref(null);

async function loadData() {
    data.value ??= (await axios.get(route('reviews.private.reviews.rating-widget', {
        campaign: props.projectId,
        dateRange: props.dateRange,
        sources: props.sources.join(','),
        reviewFormId: props.reviewFormId,
    }))).data;

    if (props.dateRange !== null) {
        compareData.value ??= (await axios.get(route('reviews.private.reviews.rating-widget', {
            campaign: props.projectId,
            dateRange: props.dateRange,
            compare: true,
            sources: props.sources.join(','),
            reviewFormId: props.reviewFormId,
        }))).data;
    }
}

function reloadData() {
    data.value = null;
    compareData.value = null;

    loadData();
}

watch(() => props.dateRange, reloadData);
watch(() => props.source, reloadData);
watch(() => props.projectId, reloadData);
watch(() => props.reviewFormId, reloadData);

onMounted(loadData);

const sourceTypes = [
    {
        type: 'yandex',
        title: 'Яндекс.Карты',
        icon: '/icons/reviews/map_yandex.svg',
    },
    {
        type: 'double-gis',
        title: '2ГИС',
        icon: '/icons/reviews/map_2gis.svg',
    },
];
</script>
