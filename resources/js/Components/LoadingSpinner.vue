<template>
    <img :src="path" alt="loading..." class="inline-block">
</template>

<script>
export default {
    name: "LoadingSpinner",
    props: {
        dark: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    computed: {
        path() {
            return this.dark ? '/icons/spinner-dark.svg' : '/icons/spinner.svg';
        },
    },
}
</script>
