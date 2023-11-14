<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="show" class="h-screen fixed inset-0 overflow-hidden sm:px-0 z-50">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>

                <transition enter-active-class="ease-out duration-300"
                            enter-from-class="opacity-0"
                            enter-to-class="opacity-100"
                            leave-active-class="ease-in duration-200"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                >
                    <div v-show="show"
                         class="h-screen py-12 inset-0 transform transition-all overflow-y-auto"
                         @click.self="close"
                    >
                        <div v-show="show"
                             class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all m-auto sm:w-full sm:mx-auto relative"
                             :class="maxWidthClass"
                        >
                            <img src="/icons/close.svg"
                                 class="absolute top-4 right-4 cursor-pointer z-50"
                                 @click="close"
                                 alt="close"
                                 v-if="closeable"
                            />
                            <slot v-if="show"></slot>
                        </div>
                    </div>
                </transition>

            </div>
        </transition>
    </teleport>
</template>

<script>
import {onMounted, onUnmounted} from "vue";

export default {
    emits: ['close'],
    props: {
        show: {
            default: false
        },
        maxWidth: {
            default: '2xl'
        },
        closeable: {
            type: Boolean,
            default: true,
        },
    },
    watch: {
        show: {
            immediate: true,
            handler: (show) => {
                if (show) {
                    document.body.style.overflow = 'hidden'
                } else {
                    document.body.style.overflow = null
                }
            }
        }
    },
    setup(props, {emit}) {
        const close = () => {
            if (props.closeable) {
                emit('close')
            }
        }
        const closeOnEscape = (e) => {
            if (e.key === 'Escape' && props.show) {
                close()
            }
        }
        onMounted(() => document.addEventListener('keydown', closeOnEscape))
        onUnmounted(() => {
            document.removeEventListener('keydown', closeOnEscape)
            document.body.style.overflow = null
        })
        return {
            close,
        }
    },
    computed: {
        maxWidthClass() {
            return {
                'sm': 'sm:max-w-sm',
                'md': 'sm:max-w-md',
                'lg': 'sm:max-w-lg',
                'xl': 'sm:max-w-xl',
                '2xl': 'sm:max-w-2xl',
            }[this.maxWidth] ?? this.maxWidth
        }
    }
}
</script>
