<template>
    <div class="analytics-settings card-md py-4 px-8">
        <form @submit.prevent="onSubmit" v-if="isLoaded">
            <h2 class="text-lg font-bold">Порядок вложенности</h2>
            <div class="pl-2 py-4">
                <div v-for="(item, index) in orderProxy"
                     :style="{'margin-left': `${(index - 1) * 8}px`}"
                     class="flex flex-row justify-start w-3/4"
                >
                    <span class="px-4 my-1 mx-2 border border-gray-300 rounded-lg flex-grow">
                        {{ $t('analytics.cabinet-item-type.' + item.type) }}
                    </span>
                    <img :src="item.hidden ? '/icons/eye-slash.svg' : '/icons/eye.svg'"
                         alt="eye"
                         class="cursor-pointer"
                         @click="onClick(index)"
                    />
                </div>
            </div>
            <validation-errors/>
            <button class="btn btn-md btn-primary w-full">Сохранить</button>
        </form>
        <div class="flex flex-row justify-center py-4" v-else>
            <loading-spinner dark/>
        </div>

        <div class="form-field mt-4">
            <checkbox v-model:checked="hideItemsWithoutExpenses">Скрыть источники без расходов</checkbox>
        </div>
    </div>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import {computed, nextTick, onBeforeMount, ref} from "vue";
import LoadingSpinner from "@/Components/LoadingSpinner.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {ls} from '@/utils';

const props = defineProps({
    campaignId: {
        required: true,
    },
});

const emit = defineEmits(['saved']);

const DEFAULT_ORDER = [
    {
        type: 'account',
        hidden: false,
    },
    {
        type: 'campaign',
        hidden: false,
    },
    {
        type: 'ad_group',
        hidden: false,
    },
    {
        type: 'ad',
        hidden: false,
    },
];

function onClick(key) {
    orderProxy.value[key].hidden = !orderProxy.value[key].hidden;
}

const loadedOrder = ref();

const orderProxy = computed({
    get: () => loadedOrder.value ?? DEFAULT_ORDER,
    set: (val) => loadedOrder.value = val,
});

const hideItemsWithoutExpenses = ls.use('analytics.settings.show-items-without-expenses', ls.BOOL);
const isLoaded = ref(false);

onBeforeMount(async () => {
    isLoaded.value = false;

    loadedOrder.value = (await axios.get(route('campaign.analytics.settings.load', props.campaignId)))
        ?.data
        ?.order ?? DEFAULT_ORDER;

    await nextTick();
    isLoaded.value = true;
});

function onSubmit() {
    useForm({
        order: orderProxy.value
    }).post(route('campaign.analytics.settings.store', props.campaignId), {
        onSuccess: () => {
            emit('saved');
        },
        only: [],
    });
}

</script>
