<template>
    <div class="flex md:flex-row md:flex-nowrap flex-col md:mt-0 mt-2 md-w-auto-full">
        <div class="form-field mr-4">
            <label class="label-sm">{{ $t('checks.filters.yandex-direct.states.label') }}</label>
            <select class="select-sm md-w-auto-full" v-model="filters.state">
                <option :value="null">{{ $t('checks.filters.yandex-direct.states.all') }}</option>
                <option v-for="state in STATE_OPTIONS"
                        :value="state"
                >{{ $t('checks.filters.yandex-direct.states.options.' + state) }}
                </option>
            </select>
        </div>
        <div class="form-field">
            <label class="label-sm">{{ $t('checks.filters.yandex-direct.statuses.label') }}</label>
            <select class="select-sm md-w-auto-full" v-model="filters.status">
                <option :value="null">{{ $t('checks.filters.yandex-direct.statuses.all') }}</option>
                <option v-for="status in STATUS_OPTIONS"
                        :value="status"
                >{{ $t('checks.filters.yandex-direct.statuses.options.' + status) }}
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

const STATE_OPTIONS = [
    'SUSPENDED',
    'ENDED',
    'ON',
    'OFF',
];

const STATUS_OPTIONS = [
    'DRAFT',
    'MODERATION',
    'ACCEPTED',
    'REJECTED',
];

const filters = ref({
    state: null,
    status: null,
});

onBeforeMount(() => {
    if (!isEmpty(props.modelValue)) {
        filters.value = props.modelValue;
    } else {
        filters.value = {
            state: 'ON',
            status: 'ACCEPTED',
        };
        emit('update:modelValue', filters.value);
    }
});

const emit = defineEmits(['update:modelValue']);

watch(filters, () => {
    emit('update:modelValue', filters.value);
}, {deep: true});

</script>
