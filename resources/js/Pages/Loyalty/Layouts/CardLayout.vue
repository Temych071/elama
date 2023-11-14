<template>
    <toast-messages/>
    <div class="w-full min-h-full sm:p-8 flex justify-center">
        <div class="sm:m-auto sm:w-[400px] w-full">
            <slot/>
        </div>
    </div>
</template>
<script setup>
import ToastMessages from "@/Components/ToastMessages.vue";
</script>
