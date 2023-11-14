<template>
    <label class="checkbox">
        <input
	        type="checkbox"
	        :disabled="disabled"
	        :value="value"
	        v-model="proxyChecked"
	        class="checkbox-component"
        >
        <span class="cursor-pointer">
            <slot />
        </span>
    </label>
</template>

<script>
export default  {
    emits: ['update:checked'],

    props: {
	    checked: {
		    type: [Array, Boolean],
		    default: false,
	    },
	    value: {
		    default: null,
	    },
	    disabled: {
		    type: Boolean,
		    required: false,
		    default: false,
	    },
    },

    computed: {
        proxyChecked: {
            get() {
                return this.checked;
            },

            set(val) {
                this.$emit("update:checked", val);
            },
        },
    },
}
</script>
