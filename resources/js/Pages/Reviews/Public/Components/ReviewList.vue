<template>
    <div
        v-if="!isEmpty2(reviewsList)"
        class="bg-[#ffffff] max-w-sm w-full md:rounded-md mx-auto md:my-8 md-2 px-2 overflow-hidden"
    >
        <div class="flex flex-col justify-between py-2 drop-shadow-md">
            <div class="flex flex-row p-2">
                <span class="font-medium">Отзывы {{ reviewsList.length }}</span>
            </div>
        </div>

        <hr>

        <div
            v-for="review in reviewsList"
            :key="review.id"
            class="flex flex-col font-medium p-2"
        >
            <div class="flex justify-between space-x-2">
                <span class="font-bold">{{ review.name }}</span>
                <span class="text-xs text-gray-500">{{ formatDate(review.created_at) }}</span>
            </div>
            <stars-input :readonly="true" v-model="review.stars"/>
            <p class="my-2">{{ review.comment }}</p>
        </div>
    </div>
</template>

<script setup>
import StarsInput from "@/Pages/Reviews/Components/StarsInput.vue";
import {dateToUserTzString, isEmpty2} from "@/utils";
import {computed} from "vue";

const props = defineProps({
    reviews: {
        type: Array,
        required: true,
    },
});

function formatDate(date) {
    return dateToUserTzString(
        date,
        {day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'},
    );
}

const reviewsList = computed(() => {
    return props.reviews.filter((review) =>
        !isEmpty2(review.comment?.trim())
        || !isEmpty2(review.name?.trim())
    );
});

</script>
