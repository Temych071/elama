<template>
    <Head>
        <title>{{ title ?? $t(this.lang) }}</title>
    </Head>
</template>

<script>
import {Head} from '@inertiajs/vue3'

export default {
    name: "PageTitle",
    components: {
        Head
    },
    props: {
        title: {type: String, default: null},
        lang: {type: String, default: null},
    }
}
</script>
