<template>
    <article class="flex justify-between">
        <span class="overflow-ellipsis overflow-hidden whitespace-nowrap text-sm">{{ item.link }}</span>
        <div class="flex items-center">
            <div
                v-if="item.status !== 'wait'"
                class="mr-4 text-sm"
            >
                {{ dateToUserTzString(item.data_updated_at).slice(0, -3) }}
            </div>
            <Tippy :content="isUpdateAvailable(item) ? 'Обновить' : 'Обновление доступно не чаще, чем раз в 10 минут'">
                <button
                    type="button"
                    @click.stop.prevent="refresh(item)"
                    :disabled="item.status === 'wait'"
                >
                    <img src="/icons/refresh-circle.svg" alt="Обновить">
                </button>
            </Tippy>
        </div>
    </article>
    <div v-if="item.status === 'wait'" class="my-2 text-sm">
        Идёт загрузка...
    </div>
    <seo-link-check-results
        v-else
        class="my-2"
        :total-score="totalScore"
        :seo-audits="item"
    />
</template>

<script setup>
import Tippy from "@/Components/Tippy.vue";
import SeoLinkCheckResults from "@/Pages/SeoAudits/Components/SeoLinkCheckResults.vue";
import {dateToUserTzString} from "@/utils";


const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    totalScore: {
        type: Number,
        default: 0,
        required: true
    }
});

const emits = defineEmits(['refresh']);

function isUpdateAvailable(item) {
    if (item.status === 'wait') {
        return false;
    }

    const diff = (new Date()).getTime() - (new Date(item.data_updated_at)).getTime();
    return diff > (10 * 1000 * 60 * 10); // 10 minutes
}

const refresh = (item) => {
    if (!isUpdateAvailable(item)) {
        return;
    }

    // console.log('refresh');

    axios.post(
        route('seo-audit.start'),
        {url: formatUrl(item.link),}
    ).then(function (response) {
        // console.log(response.data);
        emits('refresh', response.data);
    });
}

const formatUrl = (url) => {
    if (url.trim().search(/^http[s]?:\/\//) === -1) {
        return 'https://' + url;
    }
    return url;
};
</script>

<style scoped>

</style>
