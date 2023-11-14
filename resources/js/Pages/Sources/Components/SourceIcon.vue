<template>
    <Icon :path="icon" :size="size"/>
</template>

<script>
import Icon from "@/Components/Icon.vue";

export default {
    name: "SourceIcon",
    components: {Icon},
    props: {
        sourceType: String,
        size: {
            default: 17,
            type: Number,
        }
    },
    computed: {
        icon() {
            return {
                'yandex-direct': '/icons/campaign/y_direct.svg',
                'google-ads': '/icons/campaign/google_ads.svg',
                'vk': '/icons/campaign/vk-new.svg',
                'vk-lead': '/icons/campaign/vk-new.svg',
                'yandex-metrika': '/icons/campaign/y_metrika.svg',
                'google-analytics': '/icons/campaign/google_analytics.svg',
                'fb': '/icons/campaign/fb.svg',
                'avito': '/icons/campaign/avito.svg',
            }[this.sourceType];
        },
    },
}
</script>
