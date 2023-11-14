<template>
    <img v-if="isValid"
         :title="title"
         :src="src"
         :alt="status"
    >
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
});

const isValid = computed(() => {
    return ['on', 'off', 'draft', 'rejected'].includes(props.status);
});

const src = computed(() => {
    switch (props.status) {
        case 'on':
            return '/icons/play.svg';
        case 'draft':
            return '/icons/draft.svg';
        case 'rejected':
            return '/icons/red-cross.svg';
        case 'off':
            return '/icons/pause.svg';
    }
});

const title = computed(() => {
    switch (props.status) {
        case 'on':
            return 'Идут показы';
        case 'draft':
            return 'Черновик';
        case 'off':
            return 'Показы приостановлены';
    }
});

</script>
