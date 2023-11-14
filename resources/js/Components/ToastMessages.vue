<template>
    <div class="w-full top-0 left-0 sticky" style="z-index: 999999">
        <div v-if="show" class="flex items-center justify-between bg-opacity-95" :class="bgClass">
            <div class="flex items-center">
                <div class="px-4 py-2 text-white text-sm font-medium">{{ $page?.props?.toast?.message }}</div>
            </div>
            <button type="button" class="group mr-2 p-2" @click="show = false">
                <svg class="block w-2 h-2 fill-green-800 group-hover:fill-white" xmlns="http://www.w3.org/2000/svg"
                     width="235.908" height="235.908" viewBox="278.046 126.846 235.908 235.908">
                    <path
                        d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"/>
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
import { router } from '@inertiajs/vue3'

export default {
    name: "ToastMessages",
    data() {
        return {
            show: true,
        };
    },
    mounted() {
        this.show = (this.$page?.props?.toast !== null);
        router.on('invalid', (event) => {
            let res = event.detail.response.data;
            if (!res?.error) {
                return;
            }

            event.preventDefault();
            console.log(res);
            this.$page.props.toast = {
                type: 'error',
                message: res.error,
            };
        });
    },
    computed: {
        bgClass() {
            return {
                success: 'bg-success-dark',
                info: 'bg-info-dark',
                warning: 'bg-warning-dark',
                error: 'bg-error-dark',
                null: '',
            }[this.$page?.props?.toast?.type];
        },
    },
    watch: {
        '$page.props.toast': {
            handler() {
                this.show = (this.$page?.props?.toast !== null);
            },
            deep: true,
        },
    },
}
</script>
