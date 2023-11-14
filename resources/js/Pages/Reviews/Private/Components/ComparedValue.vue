<template>
    <div
        :title="cmpValue !== null ? `Было ${cmpValue}` : ''"
        class="flex items-center gap-1"
    >
        <div><slot :val="value">{{ (ifEmpty !== null && isEmpty2(value)) ? ifEmpty : value }}</slot></div>
        <template v-if="cmpValue !== null">
            <svg v-if="diff !== 0" width="10" height="10" viewBox="0 0 7 6" :fill="color" :class="arrowClass" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.84313 0.0375223C2.78108 0.0613294 2.72438 0.0970273 2.67631 0.142566L0.148671 2.64361C0.101537 2.69024 0.0641476 2.74561 0.0386385 2.80655C0.0131295 2.86748 0 2.9328 0 2.99875C0 3.13196 0.0534787 3.25971 0.148671 3.3539C0.195806 3.40054 0.251763 3.43754 0.313347 3.46278C0.374931 3.48802 0.440937 3.50101 0.507595 3.50101C0.642218 3.50101 0.771327 3.44809 0.866519 3.3539L2.5297 1.70321L2.5297 5.49979C2.5297 5.63246 2.58296 5.75969 2.67777 5.85349C2.77257 5.9473 2.90116 6 3.03523 6C3.1693 6 3.29789 5.9473 3.39269 5.85349C3.4875 5.75969 3.54076 5.63246 3.54076 5.49979L3.54076 1.70321L5.20394 3.3539C5.25094 3.40078 5.30685 3.438 5.36845 3.46339C5.43005 3.48879 5.49613 3.50186 5.56286 3.50186C5.6296 3.50186 5.69567 3.48879 5.75728 3.46339C5.81888 3.438 5.87479 3.40078 5.92179 3.3539C5.96917 3.3074 6.00678 3.25208 6.03244 3.19112C6.05811 3.13017 6.07132 3.06479 6.07132 2.99875C6.07132 2.93272 6.05811 2.86734 6.03244 2.80638C6.00678 2.74543 5.96917 2.69011 5.92179 2.64361L3.39415 0.142566C3.34608 0.0970273 3.28938 0.0613294 3.22733 0.0375223C3.10425 -0.0125074 2.96621 -0.0125074 2.84313 0.0375223Z"/>
            </svg>
            <svg v-else width="10" height="10" :fill="color" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"/>
            </svg>
            <span :style="{color: color}" class="text-xs">
                <slot name="diff" :val="Math.abs(diff)">{{ Math.abs(diff) }}{{ calcFunc === 'percent' ? '%' : '' }}</slot>
            </span>
        </template>
    </div>
</template>

<script setup>
import {computed} from "vue";
import {diffPercents, isEmpty2, r} from "@/utils";

const props = defineProps({
    value: {
        type: Number,
        required: true,
    },
    cmpValue: {
        type: Number,
        required: false,
        default: null,
    },
    calcFunc: {
        type: [Function, String],
        required: false,
        default: 'percent',
    },
    ifEmpty: {
        type: String,
        required: false,
        default: null,
    },
    reverseColor: {
        type: Boolean,
        required: false,
        default: false,
    },
    reverseArrow: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const _calcFunc = computed(() => ({
    percent: (val, cmp) => r(diffPercents(cmp, val, true), 1),
    sub: (val, cmp) => val - cmp,
}[String(props.calcFunc)] ?? props.calcFunc));

const diff = computed(() => r(_calcFunc.value(props.value, props.cmpValue)));

const color = computed(() => {
    if (diff.value > 0) {
        return !props.reverseColor ? '#76D276' : '#E96565';
    } else if (diff.value < 0) {
        return !props.reverseColor ? '#E96565' : '#76D276';
    } else {
        return '#FFA319';
    }
});

const arrowClass = computed(() => {
    if (diff.value > 0) {
        return !props.reverseArrow ? '' : 'rotate-180';
    } else if (diff.value < 0) {
        return !props.reverseArrow ? 'rotate-180' : '';
    } else {
        return '';
    }
});
</script>
