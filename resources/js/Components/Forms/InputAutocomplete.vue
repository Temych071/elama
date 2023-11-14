<template>
    <div class="w-full">
        <input
            v-bind="$attrs"
            @input="$emit('update:modelValue', $event.target.value); $refs.dd.open = true"
            :value="modelValue"

            @focusin="$refs.dd.open = true"
            @focusout="$refs.dd.open = false"
        />
        <dropdown
            ref="dd"
            align="left"
            width="full"
            :manual="true"
        >
            <template #trigger>
                <span></span>
            </template>
            <template #content>
                <div class="max-h-80 overflow-auto" @mousedown.prevent="" v-if="ddList.length">
                    <ul class="py-2 bg-background">
                        <li
                            class="py-2 px-4 hover:bg-gray-200 cursor-pointer"
                            v-for="option in ddList"
                            @click="onSelect(option)"
                        >{{ option }}
                        </li>
                    </ul>
                </div>
            </template>
        </dropdown>
    </div>
</template>

<script>
import Dropdown from "@/Components/Dropdown.vue";

export default {
    inheritAttrs: false,
    components: {Dropdown},
    emits: ['update:modelValue'],
    props: {
        options: {
            type: Array,
            required: false,
            default: [],
        },
        excepted: {
            type: Array,
            required: false,
            default: [],
        },
        modelValue: String,
    },
    computed: {
        ddList() {
            return this.options.filter((item) => (
                !this.modelValue?.length
                || item.includes(this.modelValue)
            ) && !this.excepted.includes(item));
        }
    },
    methods: {
        onSelect(option) {
            this.$emit('update:modelValue', option);
            this.$refs.dd.open = false;
        },
    },
}
</script>
