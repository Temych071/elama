<template>
    <page-title title="Отзывы"/>

    <authenticated>
        <h2 class="title mb-4">
            <span>Отзывы</span>
        </h2>

        <div class="flex xl:flex-row flex-col-reverse gap-4 w-full">
            <div class="flex-grow-1 w-full space-y-4">
                <div class="flex md:flex-row flex-col md:space-x-3 md:items-end md:space-y-0 space-y-3">
    <!--            <searchable-select v-model="filters.rating" :options="filterRating" input-classes="select-sm" class="text-xl"/>-->
                    <period-controls
                        :buttons="[]"
                        :name="`date-range`"
                        :dd-items="[
                            {title: 'За всё время', value: undefined},
                            {title: 'Вчера', value: 'yesterday'},
                            {title: 'Прошлая неделя', value: 'last-week'},
                            {title: '7 дней', value: '7days'},
                            {title: 'Месяц', value: 'month'},
                            {title: 'Прошлый месяц', value: 'last-month'},
                            {title: 'Этот год', value: 'year'},
                        ]"
                        :current-period-str="dateRange ? dateRangeFormat(dateRange) : 'За всё время'"
                    />

                    <div v-for="filter in filtersValue" class="flex flex-col">
                        <div class="text-[11px] text-gray-700">
                            {{filter.label}}
                        </div>
                        <searchable-select
                            :model-value="formFilters[filter.name]"
                            @update:model-value="router.get('', {[filter.name]: $event ?? undefined})"
                            :options="filter.options"
                            input-classes="select-sm" class="text-sm"
                        />
                    </div>
                </div>
                <div class="flex flex-row gap-3 text-sm">
                    <button
                        v-for="sourceType in sourceTypes"
                        @click="selectReviewsSource(sourceType.type)"
                        class="flex items-center gap-x-1 border-gray-300 px-1 py-0.5 rounded-lg"
                        :class="`${props.sources.includes(sourceType.type) ? 'border' : ''}`"
                    >
                        <img
                            v-if="!isEmpty2(sourceType.icon)"
                            class="h-4"
                            alt="icon"
                            :src="sourceType.icon"
                        />
                        <span>{{ sourceType.title }}</span>
                    </button>
                </div>

                <div v-if="reviews.total > 0" class="flex flex-col space-y-3">
                    <ReviewCard :key="review.id" v-for="review in reviews.data" :review="review" :campaign="campaign"/>
                </div>
                <div v-else class="text-center font-medium">
                    Отзывы не найдены
                </div>
            </div>
            <div class="xl:min-w-[350px] flex flex-col gap-4 items-stretch justify-stretch">
                <reviews-rating-widget
                    :project-id="campaign.id"
                    :date-range="dateRange"
                    :sources="sources"
                    :review-form-id="formFilters.branches ?? null"
                />
                <div v-if="!isEmpty2(tags)" class="card p-4 flex flex-wrap text-gray-700">
                    <span
                        v-for="tag in tags"
                        class="mx-2 my-0.5 cursor-pointer"
                        :class="`${selectedTag === tag ? 'underline' : 'hover:underline hover:decoration-dashed hover:decoration-1'}`"
                        @click="router.get('', {selectedTag: selectedTag === tag ? undefined : tag})"
                    >#{{ tag }}</span>
                </div>
            </div>
        </div>

        <pagination class="mt-6" :links="reviews.links"/>
    </authenticated>
</template>

<script setup>
import Authenticated from "@/Layouts/Authenticated.vue";
import PageTitle from "@/Components/PageTitle.vue";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";
import ReviewCard from "@/Pages/Reviews/Components/ReviewCard.vue";
import {ref} from "vue";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import PeriodControls from "@/Components/Forms/PeriodControls.vue";
import {dateRangeFormat} from "@/dateRange";
import ReviewsRatingWidget from "@/Pages/Reviews/Private/Components/ReviewsRatingWidget.vue";
import {isEmpty2} from "@/utils";

const props = defineProps({
    reviews: {
        required: true,
        type: Object,
    },
    campaign: {
        type: Object,
        required: true,
    },
    filtersValue: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
    dateRange: {
        type: String,
        required: false,
        default: null,
    },

    tags: {
        type: Array,
        required: false,
        default: [],
    },
    selectedTag: {
        type: String,
        required: false,
        default: null,
    },
    sources: {
        type: Array,
        required: false,
        default: [],
    },
});

const formFilters = ref({
    rating: props.filters.rating ?? props.filtersValue.rating.options[0].value,
    branches: props.filtersValue.branches.options.find((option) => {
        if (!props.filters.branches) {
            return true;
        }
        return option.value === parseInt(props.filters.branches);
    }).value,
    // comments: props.filters.comments ?? props.filtersValue.comments.options[0].value,
    answer: props.filters.answer ?? props.filtersValue.answer.options[0].value,
});

function selectReviewsSource(type) {
    if (props.sources.includes(type)) {
        let sources = props.sources;
        sources.splice(sources.indexOf(type), 1);
        router.get('', {sources: sources.join(',')});
    } else {
        router.get('', {sources: [...props.sources, type].join(',')});
    }
}

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
    {
        type: 'daily-grow',
        title: 'DailyGrow',
        icon: '/mstile-70x70.png',
    },
];

</script>
