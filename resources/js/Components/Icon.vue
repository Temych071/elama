<template>
    <img :src="path" class="inline-block" :style="styles">
</template>

<script>
export default {
    props: {
        path: String,
        size: {
            type: Number,
            default: 16,
        },
    },
    computed: {
        styles() {
            return {'width': this.size / 16 + 'em'};
        }
    }
}
</script>
