<template>
    <searchable-select
        :options="selectItems"
        v-model="activeItem"
        placeholder="-"
        input-classes="select-sm"
        class="text-sm text-gray-500"
    ></searchable-select>
</template>

<script setup>
import {computed, onBeforeMount, ref, watch} from "vue";
import axios from "axios";
import {useI18n} from 'vue-i18n';
import {isEmpty, isEmpty2, separateNumber} from "@/utils";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";

const props = defineProps({
    campaignId: {
        type: Number,
        required: true,
    },
    showSelect: {
        type: Boolean,
        default: false,
    },
    contClass: {
        type: String,
        required: false,
        default: 'w-full'
    }
});

const emit = defineEmits(['update:showSelect'])
const {t} = useI18n({useScope: 'global'});

const balances = ref({});
const activeItem = ref(null);

const selectItems = computed(() => {
    let lst = [];

    for (let cabinet in balances.value) {
        let data = balances.value[cabinet];
        lst.push({
            title: getBalanceTitle(cabinet, data),
            value: cabinet,
        });
    }

    return lst;
});

function getBalanceTitle(cabinet, data) {
    return `${t('sources.names.' + cabinet)} - ${separateNumber(data.amount)} ${data.currency}`
}

watch(balances, () => {
    if (!isEmpty(balances.value)) {
        emit('update:showSelect', true);
    } else {
        emit('update:showSelect', false);
    }

    if (isEmpty2(activeItem.value) || isEmpty2(balances.value[activeItem.value])) {
        activeItem.value = Object.keys(balances.value)[0] ?? null;
    }
})

onBeforeMount(async () => {
    balances.value = (await axios.get(route('campaign.balances.get', props.campaignId))).data;
});
</script>
