<template>
    <img v-if="up" src="/icons/summary-arrow-up.svg" alt="up" v-bind="$attrs">
    <img v-else src="/icons/summary-arrow-down.svg" alt="down" v-bind="$attrs">
</template>

<script>
export default {
    name: "SummaryArrow",
    props: {
        up: {
            type: Boolean,
            required: true,
        }
    },
}
</script>

<style scoped>
img {
    width: 24px;
    height: 24px;
    display: inline-block;
}
</style>
