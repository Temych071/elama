<template>
    <li class="breadcrumb-item" :class="{'breadcrumb-item_active': !href}">
        <NavLink :href="href" v-if="href">
            <slot/>
        </NavLink>
        <span v-else>
            <slot/>
        </span>
    </li>
</template>

<script setup>
import NavLink from "@/Components/NavLink.vue";

const props = defineProps({
    href: String,
});

</script>

<style scoped>
.breadcrumb-item {
    font-size: .9em;
    color: #2d6bc8;
}

.breadcrumb-item_active {
    color: #6c757d;
}

.breadcrumb-item + .breadcrumb-item:before {
    padding: 0 .5rem;
    color: #6c757d;
    content: "/";
}
</style>
