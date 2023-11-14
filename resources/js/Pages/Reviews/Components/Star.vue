<template>
    <svg :class="`${filled ? fillClass : notFillClass} ${muted ? 'opacity-40' : ''}`"
         class="h-full transition-all ease-in-out duration-150"

         xmlns="http://www.w3.org/2000/svg"
         width="24"
         height="24"
         viewBox="0 0 24 24"
         stroke-linecap="round"
         stroke-linejoin="round"
    >
        <polygon
            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
        />
    </svg>
</template>

<script setup>
const props = defineProps({
    filled: {
        type: Boolean,
        required: false,
        default: true,
    },
    muted: {
        type: Boolean,
        required: false,
        default: false,
    },
    fillClass: {
        type: String,
        required: false,
        default: 'fill-yellow-400 stroke-yellow-400 drop-shadow-lg',
    },
    notFillClass: {
        type: String,
        required: false,
        default: 'fill-transparent stroke-0.5 stroke-[#e7e7e7]',
    },
});
</script>
