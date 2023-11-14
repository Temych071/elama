<template>
    <div class="flex w-28 flex flex-row flex-nowrap relative">
        <div :style="{'background-color': modelValue}"
             class="cursor-pointer border-gray-200 border flex-grow rounded-l-md"
             @click="showDropdown = true"></div>
        <input type="text"
               class="px-1 py-0.5 flex-shrink-0 w-20 text-sm rounded-r-md border-gray-200"
               :value="modelValue"
               @input="emit('update:modelValue', $event.target.value)"
               maxlength="7"
               minlength="7"
               :required="required"
        />
        <color-picker
            v-if="showDropdown"
            class="absolute left-0 top-full"
            theme="light"
            :color="modelValue"
            :colors-default="palette"
            @changeColor="emit('update:modelValue', $event['hex'])"
            @mousedown.prevent.stop="showDropdown = true"
        />
    </div>
</template>

<script setup>
import {ColorPicker} from "vue-color-kit";
import {onMounted, onUnmounted, ref, watch} from "vue";

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    palette: {
        type: Array,
        required: false,
        default: ['#FFFFFF', '#F6F8FA', '#339AF0', '#44D600', '#FFA31A', '#FF1A43', '#33C2FF', '#223354', '#DCE4EA'],
    },
    required: {
        type: Boolean,
        required: false,
        default: false,
    },
    default: {
        required: false,
        default: undefined,
    },
});

const emit = defineEmits(['update:modelValue']);

const showDropdown = ref(false);

const onMouseDown = () => {
    if (showDropdown.value) {
        showDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('mousedown', onMouseDown);
    handleDefaultValue()
});

watch(() => props.modelValue, handleDefaultValue);

function handleDefaultValue() {
    // console.log(props.modelValue);
    if (props.default !== undefined && props.modelValue === undefined) {
        emit('update:modelValue', props.default);
    }
}

onUnmounted(() => {
    document.removeEventListener('mousedown', onMouseDown);
});
</script>
