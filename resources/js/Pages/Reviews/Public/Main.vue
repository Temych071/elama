<template>
    <page-title :title="pageTitle"/>

    <public-layout>
        <div class="w-full flex flex-col items-center justify-center mx-auto md:my-8 md-2 w-full">
            <review-form
                :review-form-settings="reviewFormSettings"
                :average-rating="averageRating"
                :can-submit="true"
                @review-sent="onReviewSent"
            />

            <review-list
                v-if="showReviews && !isEmpty2(reviewList)"
                class="md:min-h-fit min-h-screen"
                :reviews="reviewList"
            />
        </div>
    </public-layout>
</template>

<script setup>
import PublicLayout from "@/Pages/Reviews/Components/PublicLayout.vue";
import {computed, onBeforeMount, ref} from "vue";
import ReviewForm from "@/Pages/Reviews/Public/Components/ReviewForm.vue";
import ReviewList from "@/Pages/Reviews/Public/Components/ReviewList.vue";
import {isEmpty2} from "@/utils.js";
import PageTitle from "@/Components/PageTitle.vue";

const props = defineProps({
    reviewFormSettings: {
        type: Object,
        required: true,
    },
});

onBeforeMount(() => {
    loadAverageRating();
    loadReviews();
});

const showReviews = computed(() => props.reviewFormSettings?.page_settings?.show_reviews_list ?? true);

const averageRating = ref(4.5);
const reviewList = ref([]);

function loadAverageRating() {
    axios.get(route('reviews.public.average-rating', props.reviewFormSettings.slug)).then(({data}) => {
        averageRating.value = data ?? 4.5;
    });
}
function loadReviews() {
    axios.get(route('reviews.public.review-list', props.reviewFormSettings.slug)).then(({data}) => {
        reviewList.value = data;
    });
}

const pageTitle = computed(() => {
    if (isEmpty2(props.reviewFormSettings.phrases)) {
        return props.reviewFormSettings.name;
    }

    return props.reviewFormSettings.phrases?.page_title ?? props.reviewFormSettings.name
});

function onReviewSent() {
    loadReviews();

    window.scrollTo(0, 0);
}

</script>
