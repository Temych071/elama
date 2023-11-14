<template>
    <span :id="id">
        <slot></slot>
    </span>
</template>

<script>
import tippy from 'tippy.js';

export default {
    name: "Tippy",

    props: {
        content: {
            type: String,
            required: true,
        },
        pos: {
            type: String,
            required: false,
            default: 'top',
        },
        options: {
            type: Object,
            required: false,
            default: {},
        },
        if: {
            type: [Boolean, Function],
            required: false,
            default: true,
        },
    },

    data() {
        return {
            id: null,
            tippyInstance: null,
        };
    },

    beforeMount() {
        this.id = `tippy-${this.$.uid}`;
    },

    mounted() {
        this.tippyInstance = tippy(`#${this.id}`, {
            content: this.content,
            placement: this.pos,

            ...this.options,
        });
    },

    methods: {
        updateState() {
            if (this.show) {
                this.tippyInstance.enable();
            } else {
                this.tippyInstance.disable();
            }
        }
    },

    watch: {
        content(to) {
            for (let item of this.tippyInstance) {
                item.setContent(to);
            }
        },
        show() {
            this.updateState()
        },
    },

    computed: {
        show() {
            if (typeof this.if === 'function') {
                return this.if();
            } else {
                return this.if;
            }
        }
    },
}
</script>
