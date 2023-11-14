<template>
    <div
        class="stats-value-bar"
        :style="{'background-color': '#F6F8FA'}"
    >
        <span class="stats-value-bar__filling"
              :style="{width: `${barWidthPercents}%`, 'background-color': bgColor}"
        ></span>
        <span class="stats-value-bar__percents">{{ percents }} %</span>
        <span class="stats-value-bar__delimiter"></span>
        <span :style="{color: textColor}" class="stats-value-bar__value">{{ displayCallback(value) }}</span>
    </div>
</template>

<script setup>
import {computed} from "vue";
import {clamp, r, separateNumber} from "@/utils";

const props = defineProps({
    value: {
        type: Number,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
    displayCallback: {
        type: Function,
        required: false,
        default: val => separateNumber(val),
    },
});

const percents = computed(() => {
    if (!props.total) {
        return 0;
    }
    return r((props.value / props.total) * 100, 2);
});

const barWidthPercents = computed(() => clamp(r((props.value / props.total) * 100, 0), 5, 100));

const colorName = computed(() => {
    if (barWidthPercents.value < 15) {
        return 'red';
    } else if (barWidthPercents.value < 45) {
        return 'yellow';
    } else {
        return 'green';
    }
});

const bgColor = computed(() => ({
    green: '#D2F3D2',
    yellow: '#FFF9DB',
    red: '#FF8787',
}[colorName.value]));

const textColor = computed(() => ({
    green: '#55B955',
    yellow: '#FFD43B',
    red: '#FF8787',
}[colorName.value]));
</script>
