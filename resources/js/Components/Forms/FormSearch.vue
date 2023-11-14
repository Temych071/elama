<template>
    <text-input class="search-control" v-model.trim="search"/>
</template>

<script>
import TextInput from "@/Components/Forms/TextInput.vue";
import _ from 'lodash';
import {useForm} from '@inertiajs/vue3';

export default {
    name: "FormSearch",
    components: {TextInput},
    props: {
        name: {
            type: String,
            required: false,
            default: 'search',
        },
        submitDelay: {
            type: Number,
            required: false,
            default: 1000,
        },
    },
    data() {
        return {
            search: '',
        };
    },
    beforeMount() {
        this.search = this.route().params.search ?? '';
    },
    watch: {
        search() {
            // console.log('change: ' + this.search);
            _.debounce(() => {
                // console.log('debounce: ', useForm({search: this.search}));
                useForm({search: this.search}).get('');
            }, this.submitDelay);
        }
    },
}
</script>
