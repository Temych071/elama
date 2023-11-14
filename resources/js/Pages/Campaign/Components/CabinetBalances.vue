<template>
    <dropdown width="80">
        <template #trigger>
            <button class="btn btn-sm btn-info-light" @click="loadBalances">{{
                    $t('campaigns.balances.showButton')
                }}
            </button>
        </template>

        <template #content>
            <ul class="px-6 pt-4 pb-2 w-80" v-if="balances !== null">
                <li v-for="(data, cabinet) in balances" class="mb-2">
                    <p class="font-bold">{{ $t('sources.names.' + cabinet) }}:</p>
                    <p class="pl-2">{{ $t('campaigns.balances.rest', data) }}</p>
                    <p class="pl-2" v-if="data.dailyBudget > 0 && data.dailyType !== 'Disabled'">
                        {{ $t('campaigns.balances.dailyBudget', data) }}
                    </p>
                </li>
            </ul>
            <div class="px-6 py-4 flex justify-center" v-else>
                <loading-spinner :dark="true"/>
            </div>
        </template>
    </dropdown>
</template>

<script setup>
import {ref} from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import axios from "axios";
import LoadingSpinner from "@/Components/LoadingSpinner.vue";

const props = defineProps({
    campaignId: {
        type: Number,
        required: true,
    },
});

const balances = ref({});
balances.value = null;

async function loadBalances() {
    if (balances.value !== null) {
        return;
    }

    balances.value = (await axios.get(route('campaign.balances.get', props.campaignId))).data;
}

function onClick() {
    loadBalances();
}
</script>
