<template>
    <page-title title="SEO проверки"/>

    <h3 class="title mb-4">SEO проверки</h3>
    <seo-audit-card :history="history"/>
</template>

<script setup>
import PageTitle from "@/Components/PageTitle.vue";
import SeoAuditCard from "@/Pages/SeoAudits/Components/SeoAuditCard.vue";

const props = defineProps({history});
</script>


<script>
import layout from "@/Layouts/Authenticated.vue";

export default {layout};
</script>
