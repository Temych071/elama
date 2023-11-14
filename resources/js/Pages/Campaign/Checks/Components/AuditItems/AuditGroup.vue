<template>
    <h3 class="font-black p-2">{{name}}</h3>

    <div v-for="item of group.failed" class="border-b my-2 py-2">
        <audit-item :status="false" :item="item"/>
    </div>

    <div v-for="item of group.succeeded" class="border-b my-2 py-2">
        <audit-item :status="true" :item="item"/>
    </div>

</template>

<script setup>
import AuditItem from "@/Pages/Campaign/Checks/Components/AuditItems/AuditItem.vue";

const props = defineProps({
    group: {
        type: Object,
        required: true,
    },
    name: {
        type: String,
        required: true,
    }
})

</script>
