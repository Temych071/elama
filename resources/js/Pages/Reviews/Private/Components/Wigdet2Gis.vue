<template>
    <div class="card flex flex-col">
        <loading-block
            class="bg-gray-500/10"
            v-if="isLoading"
            spinner-dark
        />

        <div class="card-title flex flex-row justify-between mb-2 px-6">
            <span class="text-lg">
                <a :href="`https://2gis.ru/firm/${placeId}`" target="_blank">{{ title }}</a>
            </span>
            <span class="flex-shrink-0 flex items-center text-md text-gray-500">
                2GIS
            </span>
        </div>

        <div class="flex-shrink-0 flex items-center text-md px-6 font-medium text-5xl mb-4">
            <div class="mr-2">
                {{ widgetData?.rating ?? 0 }}
            </div>

            <stars-input
                readonly
                :stars-num="5"
                v-model="rating"
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
                    <h4 class="flex flex-row">
                        <div class="flex-shrink-0 font-semibold text-base flex flex-col">
                            <span>
                                <a :href="`https://2gis.ru/kirov/firm/${placeId}/tab/reviews`"
                                   target="_blank">{{ review.name }}</a>
                            </span>
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
                    </h4>
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
import {onMounted, ref} from "vue";
import {dateToUserTzString, isEmpty2} from "@/utils.js";
import {usePage} from "@inertiajs/vue3";
import StarsInput from "@/Pages/Reviews/Components/StarsInput.vue";

const props = defineProps({
    placeId: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: false,
        default: '2GIS',
    },
});

const isLoading = ref(false);
const widgetData = ref(null);
const rating = ref(0);

onMounted(loadData);

function loadData() {
    isLoading.value = true;
    axios.get(route('reviews.private.2gis.widget', {
        campaign: usePage().props.campaign,
        place_id: props.placeId,
    })).then(({data}) => {
        widgetData.value = data;
        rating.value = Math.round(widgetData.value.rating);
    }).finally(() => {
        isLoading.value = false;
    })
}

</script>
