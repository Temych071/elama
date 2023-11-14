<template>
    <page-title title="Вход в программу лояльности" />

    <card-layout>
        <login-card :loyalty="loyalty"/>
    </card-layout>
</template>

<script setup>
import CardLayout from "@/Pages/Loyalty/Layouts/CardLayout.vue";
import LoginCard from "@/Pages/Loyalty/Components/Cards/LoginCard.vue";
import PageTitle from "@/Components/PageTitle.vue";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
});

</script>
