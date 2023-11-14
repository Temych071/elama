<template>
    <article class="h-full flex flex-col justify-between">
        <h4 class="mb-2 font-medium metric-title">{{ titles[title] }}</h4>
        <div class="metric-container">
            <div class="metric-point"></div>
            <div class="metric-value">
                {{ item['percentile'] + (title === 'FIRST_CONTENTFUL_PAINT_MS' ? '' : '&nbsp;мс') }}
            </div>
            <div class="flex">
                <div v-for="i in item['distributions']"
                     class="mr-1 metric-item"
                     :style="'flex-grow: ' + i['proportion']">
                </div>
            </div>
        </div>
    </article>
</template>

<script setup>

const props = defineProps({
    title: String,
    item: Object,
});

const titles = {
    LARGEST_CONTENTFUL_PAINT_MS: 'Скорость загрузки основного контента (LCP)',
    FIRST_INPUT_DELAY_MS: 'Время ожидания до первого взаимодействия с контентом (FID)',
    CUMULATIVE_LAYOUT_SHIFT_SCORE: 'Совокупное смещение макета (CLS)',
    FIRST_CONTENTFUL_PAINT_MS: 'Первая отрисовка контента (FCP)',
    EXPERIMENTAL_INTERACTION_TO_NEXT_PAINT: 'Взаимодействие до следующей отрисовки (INP)',
    EXPERIMENTAL_TIME_TO_FIRST_BYTE: 'Время до первого байта (TTFB)',
};

</script>

<style scoped lang="scss">

.metric-container {
    position: relative;
    padding-top: 20px;
}

.metric-point {
    position: absolute;
    left: 75%;
    width: 6px;
    height: 20px;
    bottom: -8px;
    background-color: #2196f3;
    border: solid 2px white;
}

.metric-value {
    position: absolute;
    bottom: 12px;
    left: 75%;
    display: flex;
    justify-content: center;
    width: 0;
    font-size: 14px;
}

.metric-title {
    font-size: 15px;
}

.metric-item {
    height: 5px;

    &:nth-child(1) {
        background-color: #76D276;
    }

    &:nth-child(2) {
        background-color: #ffa400;
    }

    &:nth-child(3) {
        background-color: #FE7964;
    }
}
</style>
