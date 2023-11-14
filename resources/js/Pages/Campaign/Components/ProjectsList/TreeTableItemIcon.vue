<template>
    <img v-if="icon !== null" :src="icon" alt="icon" class="inline" />
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    path: {
        type: Array,
        required: true,
    },
});

const icon = computed(() => {
    switch (props.path[0]) {
        case 'yandex-direct':
            return '/icons/campaign/y_direct.svg';
        case 'vk':
            return '/icons/campaign/vk_simple.svg';
        case 'vk-leads':
            return '/icons/campaign/vk_simple.svg';
        case 'seo-yandex-metrika':
            return '/icons/seo.svg';
        case 'seo-google-analytics':
            return '/icons/seo.svg';
        case 'direct-traffic-yandex-metrika':
            return '/icons/user-solid.svg';
        case 'avito':
            return '/icons/campaign/avito.svg';
    }
    return null;
});
</script>
