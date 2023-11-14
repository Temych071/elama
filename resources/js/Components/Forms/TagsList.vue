<template>
    <div class="form-tags-list flex flex-row flex-wrap w-full">
        <div class="rounded-md flex-shrink-0 flex flex-row border border-gray-300 px-1 mr-1 mb-1 max-w-full bg-gray-100"
             v-for="(item, index) in items"
        >
            <img src="/icons/close.svg"
                 alt="remove"
                 class="mr-1 cursor-pointer"
                 @click="onDelete(index)"
            />
            <span class="text-black text-opacity-90 break-all ">{{ item }}</span>
        </div>
    </div>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    modelValue: Array,
});

const emit = defineEmits(['update:modelValue']);

const items = computed({
    get: () => props.modelValue,
    set: (v) => emit('update:modelValue', v),
});

function onDelete(index) {
    let its = items.value;
    items.value.splice(index, 1);
    items.value = its;
}

const add = (value) => {
    let its = items.value;
    its.push(value);
    items.value = its;
}
</script>
