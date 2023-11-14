<template>
    <div class="card px-4">
        <form @submit.prevent="submit" class="flex md:flex-row flex-col">
            <input type="text"
                   v-model.trim="form.url"
                   class="form-control md:mr-2 mr-0 md:mb-0 mb-2 flex-grow"
                   placeholder="URL"
                   :disabled="loadingCheckResult"
            >
            <button v-if="!lastAuditItem || lastAuditItem?.link !== form.url || loadingCheckResult"
                    type="submit"
                    class="btn btn-primary"
                    :disabled="loadingCheckResult || !form.url?.length"
            >
                Проверить
            </button>
            <a v-else class="btn btn-primary flex-shrink-0" :href="route('seo-audit.export', lastLinkUuid)">
                Скачать отчёт
            </a>
        </form>

        <ValidationErrors/>

        <section v-if="history" class="mt-10">
            <section v-if="showCurrentUrlCheck">
                <preview-item
                    :total-score="totalScore(lastAuditItem)"
                    :item="lastAuditItem"
                    class="mt-2 mx-1"
                    @refresh="onSeoAuditStart"
                />
            </section>

            <div class="flex items-center mb-3">
                <h2 class="font-medium">История проверок</h2>
                <SliderDeployButton class="ml-2" v-model:is-deployed="isDeployedHistory"/>
            </div>

            <div v-if="isDeployedHistory" v-for="item in history"
                 class="block border border-gray-200 rounded px-6 py-2 mb-2">
                <preview-item
                    :total-score="totalScore(item)"
                    :item="item"
                    @refresh="onSeoAuditStart"
                />
            </div>
        </section>
    </div>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import {r} from "@/utils";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import {ref} from "vue";
import PreviewItem from "@/Pages/SeoAudits/Components/PreviewItem.vue";
import SliderDeployButton from "@/Pages/Campaign/Checks/Components/SliderDeployButton.vue";

const props = defineProps({
    history: {
        type: Array,
        required: false,
        default: [],
    },
});

const showCurrentUrlCheck = ref(false);
const isDeployedHistory = ref(false);
const loadingCheckResult = ref(false);

const lastLinkUuid = ref();
const lastAuditItem = ref(null);

const showCheckButton = ref(true);

const form = useForm({
    url: lastAuditItem.value?.link ?? '',
});

const fetchLastAuditResult = async () => {
    const res = await axios.get(route('seo-audit.item', lastLinkUuid.value));
    if (!res.data || res.status !== 200) return;

    lastAuditItem.value = res.data;
    const loaded = lastAuditItem.value.status === 'success'
    loadingCheckResult.value = !loaded;
    showCurrentUrlCheck.value = true;
    showCheckButton.value = false;
    form.url = lastAuditItem.value?.link ?? '';

    if (!loaded) {
        setTimeout(fetchLastAuditResult, 5000);
    }
}

const totalScore = (item) => {
    const simpleResult = item.simple_result

    let score = 0;
    let countChecks = 0;
    for (const simpleResultKey in simpleResult) {
        for (const check in simpleResult[simpleResultKey]) {
            if (simpleResult[simpleResultKey][check].score === null) {
                simpleResult[simpleResultKey][check].score = 1;
            }

            score += simpleResult[simpleResultKey][check].score ?? 0;
            countChecks++;
        }
    }

    return r((score / countChecks) * 100);
};

const submit = () => {
    axios.post(route('seo-audit.start'), {url: form.url})
        .then(async (res) => {
            lastLinkUuid.value = res.data.uuid;
            await fetchLastAuditResult();
            form.url = lastAuditItem.value?.link ?? '';
        });
};
</script>
