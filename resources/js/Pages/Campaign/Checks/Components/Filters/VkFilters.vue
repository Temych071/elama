<template>
    <div class="flex md:flex-row md:flex-nowrap flex-col md:mt-0 mt-2 md-w-auto-full">
        <div class="form-field">
            <label class="label-sm">{{ $t('checks.filters.vk.statuses.label') }}</label>
            <select class="select-sm md-w-auto-full" v-model="filters.status">
                <option :value="null">{{ $t('checks.filters.vk.statuses.all') }}</option>
                <option v-for="status in STATUS_OPTIONS"
                        :value="status"
                >{{ $t('checks.filters.vk.statuses.options.' + status) }}
                </option>
            </select>
        </div>
    </div>
</template>

<script setup>
import {onBeforeMount, ref, watch} from "vue";
import {isEmpty} from "@/utils";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
});

const STATUS_OPTIONS = [0, 1];

const filters = ref({
    status: null,
});

onBeforeMount(() => {
    if (!isEmpty(props.modelValue)) {
        filters.value = props.modelValue;
    } else {
        filters.value = {
            status: 1,
        };
        emit('update:modelValue', filters.value);
    }
});

const emit = defineEmits(['update:modelValue']);

watch(filters, () => {
    emit('update:modelValue', filters.value);
}, {deep: true});

</script>
