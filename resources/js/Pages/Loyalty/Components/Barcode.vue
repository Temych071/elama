<template>
    <svg
        v-bind="$attrs"
        ref="barcodeEl"
        :id="elId"
    ></svg>
</template>

<script setup>
import JsBarcode from "jsbarcode";
import {nextTick, onMounted, ref, watch} from "vue";
import {v4 as uuid4} from "uuid";

const props = defineProps({
    value: {
        type: String,
        required: true,
    },
    options: {
        type: Object,
        required: false,
        default: {},
    },
});

const elId = ref();

function updateBarcode() {
    JsBarcode(`#${elId.value}`, props.value, props.options);
}

onMounted(() => {
    elId.value = 'barcode-' + uuid4().split('-')[0];
    nextTick(updateBarcode);
});
watch(() => props.value, updateBarcode);
</script>
