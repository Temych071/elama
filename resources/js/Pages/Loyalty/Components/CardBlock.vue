<template>
    <div class="bg-white border rounded-md relative">
        <div v-if="inactive" class="absolute top-0 right-0 bottom-0 left-0 z-[9999] flex items-center">
            <div v-if="!isEmpty2(inactiveText)" class="rounded-md p-2 bg-gray-500/20 m-2 text-center">
                {{ inactiveText }}
            </div>
        </div>
        <div
            v-if="$slots.header"
            :style="`background-color: ${headerBg}`"
            class="rounded-t-md drop-shadow-md"
        >
            <slot name="header"/>
        </div>
        <div>
            <slot/>
        </div>
    </div>
</template>

<script setup>
import {isEmpty2} from "@/utils";

const props = defineProps({
    headerBg: {
        type: String,
        required: false,
        default: '#F6F8FA',
    },
    inactive: {
        type: Boolean,
        required: false,
        default: false,
    },
    inactiveText: {
        type: String,
        required: false,
        default: '',
    },
});
</script>
