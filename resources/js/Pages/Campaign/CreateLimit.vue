<template>
    <card>
        Лимит в {{ limit }} кампаний на аккаунт достигнут.
    </card>
</template>

<script setup>
import Card from "@/Components/MetrikaSummary/Feed/Card.vue";

const props = defineProps({
    count: Number,
    limit: Number,
});
</script>

<script>
import layout from "@/Layouts/Authenticated.vue";

export default {layout};
</script>
