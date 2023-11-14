<template>
    <slot v-if="isAdmin" />
</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const isAdmin = computed(() => {
    return usePage().props.auth.user.role === 'admin';
});
</script>
