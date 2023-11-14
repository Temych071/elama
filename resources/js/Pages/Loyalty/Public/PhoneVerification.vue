<template>
    <page-title title="Подтверждение номера телефона" />

    <card-layout>
        <phone-verify-card :loyalty="loyalty" :phone="phone"/>
    </card-layout>
</template>

<script setup>
import CardLayout from "@/Pages/Loyalty/Layouts/CardLayout.vue";
import PhoneVerifyCard from "@/Pages/Loyalty/Components/Cards/PhoneVerifyCard.vue";
import PageTitle from "@/Components/PageTitle.vue";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    phone: {
        type: String,
        required: false,
        default: null,
    },
});

</script>
