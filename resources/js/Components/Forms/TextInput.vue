<template>
    <input :id="id"
           ref="input"
           v-bind="$attrs"
           class="form-control"
           :type="type"
           :placeholder="placeholder"
           :value="modelValue"
           @input="$emit('update:modelValue', $event.target.value)"
           v-maska="mask_"
           :disabled="disabled"
           :required="required"
           @click="onClick"
           :readonly="readonly"
    />
    <InputError :message="error"/>
</template>

<script>
import InputError from "@/Components/InputError.vue";

export default {
    components: {InputError},
    inheritAttrs: false,
    emits: ['update:modelValue'],
    props: {
        id: {
            type: String,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        type: {
            type: String,
            default: 'text',
        },
        mask: {
            type: [String, Object, Array, null],
            default: null,
        },
        modelValue: String,
        label: String,
        error: String,
        placeholder: String,
        required: Boolean,
        autoSelect: Boolean,
        readonly: Boolean,
    },
    methods: {
        onClick() {
            if (this.autoSelect) {
                this.select();
            }
        },
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
    },
    computed: {
        mask_() {
            switch (this.mask) {
                case 'tel':
                    return '+# (###) ###-##-##';
                default:
                    return this.mask
            }
        },
    },
}
</script>
