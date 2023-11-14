<template>
    <button @click.prevent="onClick" v-bind="$attrs" :disabled="disabled" type="button">
        <slot/>
    </button>
</template>

<script>
import {useForm} from '@inertiajs/vue3';

export default {
	name: "FormButton",
	emits: ['callback'],
	props: {
		data: {
			type: [Object, Function],
			required: false,
			default: {},
		},
		onPrepare: {
			type: [Function, null],
			required: false,
			default: null,
		},
        method: {
            type: String,
            required: false,
            default: 'get',
        },
        action: {
            type: String,
            required: false,
            default: '',
        },
        confirm: {
            type: [String, null],
            required: false,
            default: null,
        },
        disabled: {
            type: [Boolean, null],
            required: false,
            default: false,
        },
        options: {
            type: Object,
            required: false,
            default: null,
        },
    },
    methods: {
        onClick() {
            if (this.disabled) {
                return;
            }

            if (
                this.confirm !== null
                && !confirm(this.confirm)
            ) {
                return;
            }

            if (this.data instanceof Function) {
                this.data();
                return;
            }

            let prepData = {};
            if (this.onPrepare instanceof Function) {
                prepData = this.onPrepare(this.data) ?? {};
            }

            let data = {
                ...this.data,
                ...prepData,
            }

            useForm(data)[this.method.toLowerCase()](this.action, {
                onFinish: this.$emit('callback'),
            }, this.options);
        },
    },
}
</script>
