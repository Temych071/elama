<template>
    <div class="flex flex-col justify-between py-3 drop-shadow-md"
         :style="{'background-color': bgColor}"
    >
        <div class="flex flex-row justify-center items-center py-2">
            <star class="w-7 px-1"/>
            <span class="font-bold px-1">{{ r(averageRating, 1) }}</span>
            <span class="text-gray-500 px-1">{{ getPhrase('text_title') }}</span>
        </div>

        <img v-if="!isEmpty2(reviewFormSettings.logo_url)"
             alt="logo"
             :src="reviewFormSettings.logo_url"
             class="w-full max-h-32 object-contain"
        />
        <div v-else class="h-12"></div>

        <div class="py-2 text-center text-gray-500">
            {{ getPhrase('text_address') }}
        </div>
    </div>
</template>

<script setup>
import {computed} from "vue";
import {isEmpty2, r} from "@/utils.js";
import Star from "@/Pages/Reviews/Components/Star.vue";

const props = defineProps({
    reviewFormSettings: {
        type: Object,
        required: true,
    },
    getPhrase: {
        type: Function,
        required: true,
    },
    averageRating: {
        type: Number,
        required: false,
        default: 4.5,
    },
});

const bgColor = computed(() => props.reviewFormSettings?.page_settings?.colors?.background ?? 'rgb(248,248,248)');
</script>
