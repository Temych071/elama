<template>
    <Link :href="href">
        <slot/>
    </Link>
</template>

<script>
import {Link} from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },

    props: ['href'],
}
</script>
