<template>
    <div>
        <form class="flex flex-row items-center" @submit.prevent="value.push(input); input = '';">
            <input-autocomplete
                class="form-control flex-grow"
                type="text"
                v-model.trim="input"
                :options="options"
                required
                v-bind="$attrs"
                :excepted="modelValue"
            />
            <button class="btn btn-md btn-primary-light flex-shrink-0 ml-2">
                <slot name="button">Добавить</slot>
            </button>
        </form>
        <tags-list v-model="value" ref="tagsUtmCampaigns" class="py-2"/>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import TagsList from "@/Components/Forms/TagsList.vue";
import InputAutocomplete from "@/Components/Forms/InputAutocomplete.vue";

const props = defineProps({
    modelValue: {
        type: Array,
        required: false,
        default: [],
    },
    options: {
        type: Array,
        required: false,
        default: [],
    },
});

const input = ref('');

const emit = defineEmits(['update:modelValue']);
const value = computed({
    get: () => props.modelValue,
    set: (v) => emit('update:modelValue', v),
});

</script>
