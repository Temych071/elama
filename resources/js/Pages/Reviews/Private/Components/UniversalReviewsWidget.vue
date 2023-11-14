<template>
    <div class="card flex flex-col">
        <loading-block
            class="bg-gray-500/10"
            v-if="isLoading"
            spinner-dark
        />

        <div class="card-title flex flex-row justify-between mb-2 px-6">
            <span class="text-lg">
                <a :href="placeUrl" target="_blank">{{ title }}</a>
            </span>
            <span class="flex-shrink-0 flex items-center text-md text-gray-500">
                {{ sourceTitle }}
            </span>
        </div>

        <div class="flex-shrink-0 flex items-center text-md px-6 font-medium text-5xl mb-4">
            <div class="mr-2">
                {{ r(n(widgetData?.rating), 2) }}
            </div>

            <stars-input
                readonly
                :stars-num="5"
                :model-value="r(n(widgetData?.rating), 0)"
                star-class="w-5"
            />
        </div>

        <p
            class="px-6 text-sm"
            v-if="!isEmpty2(widgetData?.reviews)"
        >Всего отзывов: {{ widgetData?.totalCount ?? 0 }}</p>

        <div v-if="!isEmpty2(widgetData?.reviews)" class="space-y-4 overflow-y-auto">
            <div>
                <div v-for="review in widgetData.reviews.slice(0, 4)" class="py-2 border-t border-gray-500/10 px-6">
                    <div class="flex flex-row">
                        <div class="flex-shrink-0 font-semibold text-base flex flex-col">
                            <h4>
                                <a :href="getReviewUrl(review)"
                                   target="_blank">{{ review.name }}</a>
                            </h4>
                            <stars-input :model-value="review.rating" readonly star-class="w-5"/>
                        </div>
                        <span class="flex-grow"></span>
                        <span class="flex-shrink-0 text-[#999] text-base">{{
                                dateToUserTzString(review.date, {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                })
                            }}</span>
                    </div>
                    <p class="mt-2 text-base leading-5">{{ review.text }}</p>
                </div>
            </div>
        </div>
        <p class="px-6" v-else>
            Отзывы не найдены.
        </p>
    </div>
</template>

<script setup>
import LoadingBlock from "@/Components/LoadingBlock.vue";
import {computed} from "vue";
import {dateToUserTzString, isEmpty2, r, n} from "@/utils.js";
import StarsInput from "@/Pages/Reviews/Components/StarsInput.vue";

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: '',
    },
    widgetData: {
        type: Object,
        required: true,
    },
});

const sourceTitle = computed(() => ({
    'double-gis': '2GIS',
    'yandex': 'Яндекс.Карты',
}[props.widgetData.source] ?? ''));

const placeUrl = computed(() => ({
    'double-gis': `https://2gis.ru/firm/${props.widgetData.placeId}`,
    'yandex': `https://yandex.ru/web-maps/org/${props.widgetData.placeId}/`,
}[props.widgetData.source] ?? ''));

function getReviewUrl(review) {
    switch (props.widgetData.source) {
        case 'double-gis':
            return `https://2gis.ru/kirov/firm/${props.widgetData.placeId}/tab/reviews`;
        case 'yandex':
            return `https://yandex.ru/web-maps/org/${props.widgetData.placeId}/reviews?reviews[publicId]=${review.id}`;
    }
}

</script>
