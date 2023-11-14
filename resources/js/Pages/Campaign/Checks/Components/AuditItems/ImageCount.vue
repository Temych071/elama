<template>
    <div class="flex items-center">
        <check-status-icon :status="status" class="mr-2"/>
        <span class="font-bold">{{ getTitle(item) }}</span>
        <slider-deploy-button v-model:is-deployed="isDeployed"/>
    </div>
    <template v-if="isDeployed">
        <ul v-if="item.score" class="ml-8">
            <li v-for="(el, index) in item.description">
                <span class="font-bold">
                    {{ el.type + ' - ' + el.count }}
                </span>
                <slider-deploy-button v-model:is-deployed="deployedTypes[index]" />

                <ul v-if="deployedTypes[index] ?? false" class="pl-4">
                    <li v-for="url in el.urls" class="mb-2">
                        <span v-if="props.item.is_headers ?? false">{{ url }}</span>
                        <a v-else :href="url" target="_blank" class="link">{{ formatUrl(url) }}</a>
                    </li>
                </ul>
            </li>
        </ul>
        <p v-else>{{ getDesc(item) }}</p>
    </template>
</template>

<script setup>
import {ref} from "vue";
import CheckStatusIcon from "@/Pages/Campaign/Checks/Components/CheckStatusIcon.vue";
import SliderDeployButton from "@/Pages/Campaign/Checks/Components/SliderDeployButton.vue";
import {useI18n} from "vue-i18n";

const props = defineProps({
    item: {type: Object, required: true},
    status: {type: Boolean, required: true}
});

const isDeployed = ref(false);
const deployedTypes = ref({});

const formatUrl = (url) => {
    const withoutDomain = url.replace(/^http[s]?:\/\/[^\/]+/i, '');

    if (withoutDomain.length > 45) {
        return withoutDomain.substring(0, 45) + '...';
    }

    return withoutDomain;
};

const i18n = useI18n();
function getTitle(item) {
    return i18n.t(`seo-audit.${item.__key}.${item.score ? 'success' : 'failed'}.title`, item, String(item.title));
}

function getDesc(item) {
    return i18n.t(`seo-audit.${item.__key}.${item.score ? 'success' : 'failed'}.desc`, item, String(item.description));
}

</script>

<style scoped>

</style>
