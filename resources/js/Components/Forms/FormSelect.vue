<template>
    <dropdown class="inline-block" :class="contClass">
	    <template #trigger>
		    <button v-bind="$attrs" class=" text-left" type="button" v-html="active ?? '-'"></button>
	    </template>
	    <template #content>
		    <div class="dropdown">
                <template v-for="option in options">
                    <a
                        @click.prevent="onSelect(option.data)"
                        v-if="option.allowHtml"
                        v-html="option.name"
                    />
                    <a
                        v-else
                        @click.prevent="onSelect(option.data)"
                    >{{ option.name }}</a>
                </template>
            </div>
        </template>
    </dropdown>
</template>

<script>
import {useForm} from '@inertiajs/vue3';
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

export default {
    name: "FormSelect",
    components: {
        DropdownLink,
        Dropdown,
    },
    inheritAttrs: false,
    props: {
        options: {
            type: Array,
            required: true,
        },
        method: {
            type: String,
            required: false,
            default: 'get',
        },
	    isActiveItem: {
		    type: [Function, null],
		    required: false,
		    default: null,
	    },
	    activeItem: {
		    type: [String, Object, null],
		    required: false,
		    default: null,
	    },
	    contClass: {
		    type: [String, Object, Array],
		    required: false,
		    default: "",
	    },
    },
    computed: {
        active() {
            let item = this.activeItem;
            if (item === null) {
                item = (() => {
                    if (this.isActiveItem instanceof Function) {
                        for (let option in this.options) {
                            if ((this.isActiveItem(option))) {
                                return option;
                            }
                        }
                    }
                    return null;
                })();
            }

            if (item instanceof Object) {
                return item.name;
            }

            return item;
        },
    },
    methods: {
        onSelect(data) {
            if (data instanceof Function) {
                data = data();
            }

            if (data instanceof Object) {
                useForm(data)[this.method.toLowerCase()]('');
            }
        }
    },
}
</script>
