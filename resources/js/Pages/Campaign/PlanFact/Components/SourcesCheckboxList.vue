<template>
    <div>
        <div>
            <Checkbox v-model:checked="isSelectedAll"
                      class="pb-1.5"
                      ref="cbSelectAll"
            >{{ selectAllLabel }}
            </Checkbox>
        </div>

        <div v-if="!isListHidden" class="mt-4">
            <div v-for="(item, _, index) in items" :key="index">
                <Checkbox
                    v-model:checked="selectProxy"
                    :value="item"
                    class="pb-1.5"
                >{{ getTitle(item) }}
                </Checkbox>
            </div>
            <div v-if="hasSeo">
                <Checkbox
                    v-model:checked="selectSeoProxy"
                    :value="seo"
                    class="pb-1.5"
                >
                    Трафик из поисковых систем (SEO)
                </Checkbox>
            </div>
        </div>
    </div>
</template>

<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import {computed, nextTick, onMounted, ref, watch} from "vue";

/*
* Очень странный костыль :)
* */

const emit = defineEmits(['update:modelValue', 'update:seo']);

const props = defineProps({
    items: Array,
    modelValue: [Array, null],
    seo: [Boolean],
    hasSeo: {
        type: Boolean,
        required: false,
        default: true
    },

    selectAllLabel: {
        type: String,
        required: true,
    },
    nullIfSelectedAll: {
        type: Boolean,
        required: false,
        default: false,
    },

    titleKey: {
        type: [String, Number, Function, null],
        required: false,
        default: null,
    },
});

const isSelectedAll = ref(false);

function getTitle(item) {
    if (props.titleKey instanceof Function) {
        return props.titleKey(item);
    }

    if (props.titleKey === null) {
        return item;
    }

    return item[props.titleKey];
}

onMounted(async () => {
    await updateSelectAllState();
});

const ignoreSleAllWatcher = ref(false);
watch(isSelectedAll, (to) => {
    if (!ignoreSleAllWatcher.value) {
        emit('update:modelValue', to ? getValues(null, to) : []);
        emit('update:seo', props.hasSeo ? to : false);
    }
});

const selectProxy = computed({
    get: () => props.modelValue,
    set: async (v) => {
        emit('update:modelValue', isSelectedAll.value ? getValues(v) : v);
        await updateSelectAllState(v);
    },
});

const selectSeoProxy = computed({
    get: () => props.seo,
    set: async (v) => {
        emit('update:seo', props.hasSeo ? v : false);
        await updateSelectAllState(null, v);
    },
});

async function updateSelectAllState(values = null, seoValue = null) {
    if (!props.nullIfSelectedAll) {
        ignoreSleAllWatcher.value = true;

        let seo = props.hasSeo
            ? (seoValue ?? selectSeoProxy.value)
            : true;

        isSelectedAll.value = (
            (values ?? selectProxy.value)?.length === props.items.length
            && seo
        );

        await nextTick();
        ignoreSleAllWatcher.value = false;
    } else {
        isSelectedAll.value = (values ?? selectProxy.value) === null;
    }
}

function getValues(values = null, isAll = null) {
    if (props.nullIfSelectedAll) {
        return (isAll ?? isSelectedAll.value) ? null : [];
    }

    return (isAll ?? isSelectedAll.value) ? (values ?? props.items) : [];
}

const isListHidden = computed(() => {
    return (props.nullIfSelectedAll && isSelectedAll.value);
});

</script>
