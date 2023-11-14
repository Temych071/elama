<template>
    <div class="flex md:flex-row flex-col md:justify-start justify-center md:items-stretch items-center">
        <div class="relative flex-shrink-0 aspect-video w-48 cursor-pointer">
            <input
                type="file"
                class="opacity-0 t-0 l-0 h-full w-full absolute cursor-pointer"
                @input="onUpload($event.target.files[0] ?? null)"
                :accept="accept.join(',')"
                ref="elInputFile"
                v-if="!displaySrc"
            >
            <a v-if="displaySrc" :href="displaySrc" target="_blank" download>
                <img
                     alt="uploadedImage"
                     :src="displaySrc"
                     class="object-contain h-full w-full"
                />
            </a>
            <div v-else
                 class="border-dashed border-2 border-gray-400 text-gray-400 rounded-md flex justify-center items-center h-full w-full"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 448 512"
                     class="w-8 h-8"
                >
                    <path
                        class="fill-current"
                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                    />
                </svg>
            </div>

            <div class="absolute top-0.5 right-0.5">
                <template v-if="deleted !== null && !isEmpty2(uploadedImage)">
                    <svg
                        v-if="deleted === false"
                        class="w-4 h-4 cursor-pointer fill-gray-300"
                        @click="onDelete"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            d="M175 175C184.4 165.7 199.6 165.7 208.1 175L255.1 222.1L303 175C312.4 165.7 327.6 165.7 336.1 175C346.3 184.4 346.3 199.6 336.1 208.1L289.9 255.1L336.1 303C346.3 312.4 346.3 327.6 336.1 336.1C327.6 346.3 312.4 346.3 303 336.1L255.1 289.9L208.1 336.1C199.6 346.3 184.4 346.3 175 336.1C165.7 327.6 165.7 312.4 175 303L222.1 255.1L175 208.1C165.7 199.6 165.7 184.4 175 175V175zM512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 48C141.1 48 48 141.1 48 256C48 370.9 141.1 464 256 464C370.9 464 464 370.9 464 256C464 141.1 370.9 48 256 48z"
                        />
                    </svg>
                    <!-- Можно добавить кнопку отмены удаления -->
                </template>
            </div>
        </div>
        <div class="flex-grow relative flex min-h-full items-center text-gray-400 pl-4 pr-8 md:mt-0 mt-2">
            <div>
                <slot/>
            </div>
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
    elInputFile.value = '';
}

</script>
