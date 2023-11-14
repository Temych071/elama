<template>
    <teleport to="body">
        <div
            v-show="shown"
            class="animation-pulse z-[9999] fixed drop-shadow-2xl top-2 left-2 right-2 w- min-w-fit m-4 transition-all rounded-md p-4 bg-white/95 text-sm text-black"
        >
            <div class="relative">
                <div class="absolute right-0 top-0 w-2 h-2 transition-all hover:scale-110 cursor-pointer"
                     @click="emit('update:shown', false)"
                >x</div>
                <slot/>
            </div>
        </div>
    </teleport>
</template>

<script setup>
const props = defineProps({
    shown: {
        type: Boolean,
        required: false,
        default: true,
    },
});

const emit = defineEmits(['update:shown']);
</script>
