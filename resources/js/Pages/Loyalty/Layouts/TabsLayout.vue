<template>
    <div>
        <div class="flex flex-row gap-6 mb-4">
            <nav-link
                v-for="tab in tabs"
                :href="route(tab.route, routeParams)"
                class="py-1"
                :class="{
                    'border-b border-primary': route().current(tab.checkRoute ?? tab.route),
                }"
            >{{ tab.title }}</nav-link>
        </div>

        <slot/>
    </div>
</template>

<script setup>
import NavLink from "@/Components/NavLink.vue";

const props = defineProps({
    tabs: {
        type: Array,
        required: true,
    },
    routeParams: {
        required: false,
        default: undefined,
    },
});
</script>
