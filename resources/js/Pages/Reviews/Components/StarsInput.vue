<template>
    <div class="flex flex-row flex-nowrap items-center">
        <star v-for="i in starsNum"
              :filled="i <= modelValue || modelValue === 0"
              @click="onClick(i)"
              :class="`${starClass} ${readonly ? '' : 'cursor-pointer hover:scale-105'}`"
              :muted="modelValue === 0"
              style="-webkit-tap-highlight-color: transparent;"
        />
    </div>
</template>

<script setup>
import {computed} from "vue";
import {clamp} from "@/utils.js";
import Star from "@/Pages/Reviews/Components/Star.vue";

const props = defineProps({
    modelValue: {
        type: Number,
        required: true,
    },
    starsNum: {
        type: Number,
        required: false,
        default: 5,
    },
    starClass: {
        type: String,
        required: false,
        default: '',
    },
    readonly: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

const starsProxy = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', clamp(val, 0, props.starsNum)),
});

function onClick(val) {
    if (props.readonly) {
        return;
    }

    if (starsProxy.value !== val) {
        starsProxy.value = val;
    }
}

</script>
