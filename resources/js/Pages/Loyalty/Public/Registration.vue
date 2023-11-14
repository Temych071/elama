<template>
    <page-title title="Регистрация в программе лояльности" />

    <card-layout>
        <reg-card
            :loyalty="loyalty"
            :free-cards-exists="freeCardsExists"
            :latest-form="latestForm"
        />
        <div class="flex justify-center py-2">
            <span class="text-xs underline text-gray-500 cursor-pointer" @click="onLogout">Выйти</span>
        </div>
    </card-layout>
</template>

<script setup>
import CardLayout from "@/Pages/Loyalty/Layouts/CardLayout.vue";
import RegCard from "@/Pages/Loyalty/Components/Cards/RegCard.vue";
import PageTitle from "@/Components/PageTitle.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    freeCardsExists: {
        type: Boolean,
        required: false,
        default: true,
    },
    latestForm: {
        type: Boolean,
        required: false,
        default: true,
    },
});

function onLogout() {
    if (props.inactive) {
        return;
    }

    router.delete(route('loyalty.public.logout', props.loyalty.id));
}

</script>
