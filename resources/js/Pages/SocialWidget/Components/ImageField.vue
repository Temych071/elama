<template>
    <div class="flex md:flex-row flex-col md:justify-start justify-center items-stretch">
        <div class="relative flex-shrink-0 aspect-square w-16 h-16 cursor-pointer">
            <input
                type="file"
                class="opacity-0 t-0 l-0 h-full w-full absolute cursor-pointer"
                @input="onUpload($event.target.files[0] ?? null)"
                :accept="accept.join(',')"
                ref="elInputFile"
            >
            <img v-if="displaySrc"
                 alt="uploadedImage"
                 :src="displaySrc"
                 class="object-fit h-full w-full rounded-full aspect-square border-2"
                 :style="{'border-color': borderColor}"
            />
            <img v-else
                 alt="upload"
                 src="/icons/social-widget/add-image.png"
                 class="h-full w-full"
            >
        </div>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import {isEmpty2} from "@/utils.js";

const props = defineProps({
    src: {
        type: String,
        required: false,
        default: null,
    },
    modelValue: {
        required: true,
    },
    deleted: {
        type: Boolean,
        required: false,
        default: null,
    },
    accept: {
        type: Array,
        required: false,
        default: ['image/png', 'image/jpeg'],
    },
    borderColor: {
        type: String,
        required: false,
        default: '#1975FF',
    },
});

const emit = defineEmits(['update:modelValue', 'update:deleted']);

const elInputFile = ref(null);

const displaySrc = computed(() => {
    if (props.deleted) {
        return null;
    }

    return uploadedImage.value;
});

const uploadedImage = computed(() => {
    if (!isEmpty2(props.modelValue)) {
        return URL.createObjectURL(props.modelValue);
    }

    if (!isEmpty2(props.src)) {
        return props.src;
    }

    return null;
});

function onUpload(file) {
    emit('update:deleted', false);
    emit('update:modelValue', file);
}

function onDelete() {
    emit('update:deleted', !props.deleted);
    emit('update:modelValue', null);
    elInputFile.value.value = '';
}

</script>
