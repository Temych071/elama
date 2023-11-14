<template>
    <page-title title="Программа лояльности" />

    <card-layout>
        <loyalty-card
            :loyalty="loyalty"
            :client="client"
        />
        <div class="flex justify-center py-2">
            <span class="text-xs underline text-gray-500 cursor-pointer" @click="onLogout">Выйти</span>
        </div>
    </card-layout>
</template>

<script setup>
import CardLayout from "@/Pages/Loyalty/Layouts/CardLayout.vue";
import LoyaltyCard from "@/Pages/Loyalty/Components/Cards/LoyaltyCard.vue";
import PageTitle from "@/Components/PageTitle.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    client: {
        type: Object,
        required: true,
    },
});

function onLogout() {
    if (props.inactive) {
        return;
    }

    router.delete(route('loyalty.public.logout', props.loyalty.id));
}
</script>
