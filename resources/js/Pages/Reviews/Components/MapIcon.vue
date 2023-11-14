<template>
    <span class="w-6 h-6 flex flex-row items-center justify-center">
        <img v-if="imgSrc !== null" class="w-full" alt="map" :src="imgSrc">
    </span>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    url: {
        type: String,
        required: true,
    },
});

const imgSrc = computed(() => {
    let hostname;
    try {
        hostname = (new URL(props.url)).hostname;
    } catch {
        return null;
    }
    const icon = (name, ext = 'svg') => `/icons/reviews/${name}.${ext}`;

    if (hostname.includes('market.yandex')) {
        return icon('Yandex_Market_2021');
    } else if (hostname.includes('eda.yandex')) {
        return icon('yandex-eda');
    } else if (hostname.includes('yandex')) {
        return icon('map_yandex');
    } else if (hostname.includes('google')) {
        return icon('map_google');
    } else if (hostname.includes('2gis')) {
        return icon('map_2gis');
    } else if (hostname.includes('ozon')) {
        return icon('ozon-logo');
    } else if (hostname.includes('zoon')) {
        return icon('zoon-logo');
    } else if (hostname.includes('prodoctorov')) {
        return icon('prodoctorov-logo', 'png');
    } else if (hostname.includes('tripadvisor')) {
        return icon('tripadvisor-logo');
    } else if (hostname.includes('delivery-club')) {
        return icon('dc_logo_horizontal');
    } else if (hostname.includes('wildberries')) {
        return icon('wildberries', 'png');
    } else if (hostname.includes('irecommend')) {
        return icon('irecommend-logo', 'jpg');
    } else if (hostname.includes('flamp')) {
        return icon('flamp-logo', 'png');
    } else if (hostname.includes('avito')) {
        return icon('avito-logo', 'png');
    } else {
        return null;
    }
});

</script>
