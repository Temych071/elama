<template>
    <div
        class="absolute flex flex-row items-start justify-center p-8 z-[9999]"
        :class="{
            '-top-2 -left-2 -bottom-2 -right-2 rounded-md': paddings,
            'top-0 left-0 right-0 bottom-0 rounded-[inherit]': !paddings,
        }"
    >
        <div class="flex flex-col justify-center items-center">
            <loading-spinner :dark="spinnerDark"/>
            <div>
                <slot>Загрузка...</slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import LoadingSpinner from "@/Components/LoadingSpinner.vue";

const props = defineProps({
    spinnerDark: {
        type: Boolean,
        required: false,
        default: false,
    },
    paddings: {
        type: Boolean,
        required: false,
        default: false,
    },
});
</script>
