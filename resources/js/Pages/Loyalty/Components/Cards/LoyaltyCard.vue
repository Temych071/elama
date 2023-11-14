<template>
    <card-block :header-bg="loyalty?.card_settings?.header_color">
        <template #header>
            <div class="py-2 px-4 flex items-center">
                <img
                    v-if="!isEmpty2(loyalty.card_logo_url)"
                    alt="logo"
                    :src="loyalty.card_logo_url"
                    class="h-10"
                />
                <div class="flex-grow"></div>
                <div
                    v-if="loyalty.card_settings.show_balance"
                    class="flex flex-col items-end"
                >
                    <span>Баланс</span>
                    <span>{{ client.card.balance / 100 }}</span>
                </div>
            </div>
            <hr/>
            <div class="py-2 px-4 flex">
                <div class="flex flex-col">
                    <span class="text-lg">{{ loyalty.card_settings.title }}</span>
                    <span class="text-xs text-gray-500">{{ loyalty.card_settings.desc }}</span>
                </div>
            </div>
            <img
                v-if="!isEmpty2(loyalty.card_banner_url)"
                alt="banner"
                :src="loyalty.card_banner_url"
                class="w-full object-cover"
            />
            <div class="py-2 px-4 flex">
                <div
                    v-if="loyalty.card_settings.show_discount && discount_percent !== null"
                    class="flex flex-col"
                >
                    <span>Размер скидки</span>
                    <span>{{ discount_percent }}%</span>
                </div>
                <div class="flex-grow"></div>
                <div
                    v-if="loyalty.card_settings.show_name && !isEmpty2(client?.form?.name)"
                    class="flex flex-col items-end"
                >
                    <span>Имя</span>
                    <span>{{ client?.form?.name }}</span>
                </div>
                <div></div>
            </div>
        </template>

        <loading-block v-if="processing" class="bg-gray-500/25 items-center" spinner-dark/>

        <div class="flex flex-col items-center p-4 space-y-4">
            <barcode :value="client.card.card_number"/>
        </div>

        <div class="flex py-2 px-4 flex justify-between text-sm underline text-gray-500">
            <div class="cursor-pointer" @click="addGoogleWallet">Google Wallet</div>
            <div class="cursor-pointer" @click="addAppleWallet">Apple Wallet</div>
        </div>
    </card-block>
</template>

<script setup>
import CardBlock from "@/Pages/Loyalty/Components/CardBlock.vue";
import Barcode from "@/Pages/Loyalty/Components/Barcode.vue";
import {isEmpty2} from "@/utils";
import {computed, nextTick, ref} from "vue";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    inactive: {
        type: Boolean,
        required: false,
        default: false,
    },
    client: {
        type: Object,
        required: false,
        default: {
            form: {
                name: 'Алексей',
            },
            card: {
                card_number: 123123123,
                discount: 10,
                balance: 380,
            },
        },
    },
});

const discount_percent = computed(() => props.client.card.discount ?? props.loyalty.card_settings.discount_percent ?? null);

const processing = ref(false);

async function addGoogleWallet() {
    if (props.inactive) {
        return;
    }

    processing.value = true;
    await nextTick();
    location.href = (await axios.post(route('loyalty.public.card.add-google', props.loyalty.id))).data;
}

async function addAppleWallet() {
    if (props.inactive) {
        return;
    }

    processing.value = true;
    await nextTick();
    location.href = route('loyalty.public.card.add-apple', props.loyalty.id);
    setTimeout(() => processing.value = false, 1000);
}
</script>
