<template>
    <div>
        <div
            class="searchable-select"
            :class="`${disabled ? 'searchable-select_disabled' : ''}`"
        >
            <input type="text"
                   :class="inputClasses"
                   class="searchable-select__size-input"
                   :value="modelValue"
                   :disabled="disabled"
                   :required="required"
            >

            <input
                type="text"
                :value="displayValue"
                @input="search = $event.target.value"
                :class="inputClasses"
                ref="searchInputEl"
                :data-focused="isFocused"
                :data-muted="!isFocused && !selectedOption"
                :placeholder="!selectedOption ? placeholder : displayCallback(selectedOption)"
                @focus="focusin"
                @blur="focusout"
                class="searchable-select__searchInput"
                :disabled="disabled"
            >
            <div v-if="!isFocused"
                 class="searchable-select__fake-input"
                 :class="inputClasses"
                 @click="focusin"
                 :data-muted="!selectedOption"
                 ref="fakeInputEl"
            >
                <div
                    class="searchable-select__disable-block"
                    v-if="disabled"
                ></div>

                <span v-if="isPlaceholderShow" :class="placeholderClasses">
                    {{ !selectedOption ? (placeholder === '' ? ' ' : placeholder) : displayCallback(selectedOption) }}
                </span>
                <span v-else>
                    <slot name="item" :option="selectedOption" :selected="true">
                        {{ displayCallback(selectedOption) }}
                    </slot>
                </span>
            </div>

            <div class="searchable-select__options"
                 :class="listClasses"
                 v-show="isFocused"
                 @mousedown.capture.prevent.stop="focusin"
            >
                <ul>
                    <template v-if="searchedOptions.length">
                        <li v-for="option in searchedOptions"
                            :key="option"
                            @click="select(option)"
                            :title="displayCallback(option)"
                            :data-selected="valueCallback(option) === modelValue"
                        >
                            <div :class="itemClasses">
                                <slot name="item" :option="option" :selected="false">
                                    {{ displayCallback(option) }}
                                </slot>
                            </div>
                        </li>
                    </template>
                    <li v-else class="searchable-select__not-found">
                        <slot name="notFound">
                            Ничего не найдено
                        </slot>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, onUnmounted, ref} from "vue";

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    modelValue: {
        required: true,
        default: null,
    },
    placeholder: {
        type: String,
        required: false,
        default: '',
    },
    inputClasses: {
        type: String,
        required: false,
        default: '',
    },
    itemClasses: {
        type: String,
        required: false,
        default: '',
    },
    listClasses: {
        type: String,
        required: false,
        default: 'max-h-48',
    },
    placeholderClasses: {
        type: String,
        required: false,
        default: 'text-gray-dark text-opacity-40',
    },
    ignoreCase: {
        type: Boolean,
        required: false,
        default: false,
    },
    disabled: {
        type: Boolean,
        required: false,
        default: false,
    },

    searchCallback: {
        type: Function,
        required: false,
        default: function (option, search) {
            if (this.ignoreCase) {
                // noinspection JSUnresolvedFunction
                return (new RegExp(search, 'i')).test(this.displayCallback(option));
            }
            // noinspection JSUnresolvedFunction
            return this.displayCallback(option).includes(search);
        },
    },

    valueCallback: {
        type: Function,
        required: false,
        default: function (option) {
            if (!(option instanceof Object)) {
                return option;
            }
            // noinspection JSUnresolvedVariable
            return option[this.valueField];
        },
    },
    valueField: {
        type: String,
        required: false,
        default: 'value',
    },

    displayCallback: {
        type: Function,
        required: false,
        default: function (option) {
            if (!(option instanceof Object)) {
                return option;
            }
            // noinspection JSUnresolvedVariable
            return option[this.displayField];
        },
    },
    displayField: {
        type: String,
        required: false,
        default: 'title',
    },

    defaultValue: {
        required: false,
        default: null,
    },

    required: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);
const fakeInputEl = ref(null);
const searchInputEl = ref(null);
const search = ref('');
const isFocused = ref(false);

// TODO: Добавить переключение между пунктами стрелочками (не представляю как:) )

const onMouseDown = () => {
    if (isFocused.value) {
        focusout();
    }
};

onMounted(() => {
    document.addEventListener('mousedown', onMouseDown);

    if (props.defaultValue !== null && (props.modelValue === undefined || props.modelValue === null)) {
        emit('update:modelValue', props.defaultValue);
    }
});

onUnmounted(() => {
    document.removeEventListener('mousedown', onMouseDown);
});

const searchedOptions = computed(() => {
    if (!search.value?.length) {
        return props.options;
    }

    return props.options
        .filter((option) => props.searchCallback(option, search.value));
});

const displayValue = computed(() => {
    if (isFocused.value) {
        return search.value;
    }

    if (selectedOption.value) {
        return props.displayCallback(selectedOption.value);
    }

    return '';
});

const isPlaceholderShow = computed(() => !(isFocused.value || selectedOption.value));

const selectedOption = computed(() => {
    return props.options
        .find((option) => props.valueCallback(option) === props.modelValue) ?? null;
});

function select(option) {
    search.value = '';
    emit('update:modelValue', props.valueCallback(option));
    focusout();
}

function focusin() {
    if (!props.disabled) {
        isFocused.value = true;
        // noinspection JSUnresolvedVariable
        searchInputEl.value?.focus();
    }
}

function focusout() {
    isFocused.value = false;
    // noinspection JSUnresolvedVariable
    searchInputEl.value?.blur();
}

</script>

<style lang="scss" scoped>
.searchable-select {
    width: 100%;
    position: relative;

    &.searchable-select_disabled .searchable-select__fake-input{
        overflow: hidden!important;
        cursor: not-allowed!important;
    }

    .searchable-select__disable-block {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 9999;

        background-color: rgba(0, 0, 0, .05);
    }

    &__searchInput, &__fake-input, &__size-input {
        position: relative;

        writing-mode: horizontal-tb !important;
        text-rendering: auto;
        letter-spacing: normal;
        word-spacing: normal;
        //line-height: normal;
        text-transform: none;
        text-indent: 0;
        text-shadow: none;
        display: block;
        text-align: start;
        appearance: auto;
        -webkit-rtl-ordering: logical;
        margin: 0;
        border-width: 1px;
        border-image: initial;
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
        color: inherit;
        cursor: pointer;
        word-break: keep-all;
        white-space: nowrap;
        width: 100%;
        max-width: 100%;
        min-width: 100%;

        &[data-focused=true] {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            cursor: text;
        }

        &[data-focused=false] {
            //float: left;
            z-index: -9999;
        }

        &[data-muted=true] {
            color: gray;
        }

        // Стрелка справа
        background: url("/icons/arrow_down.svg") no-repeat right 6px center;
        padding-right: 30px;
        //padding: 8px 30px 8px 12px;
    }

    &__fake-input > span {
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
        max-width: 100%;
        min-width: 100%;
    }

    &__size-input {
        padding-bottom: 0 !important;
        padding-top: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        border-top: none !important;
        border-bottom: none !important;
        height: 0 !important;
        display: block !important;
        opacity: 0 !important;
    }

    &__searchInput {
        &[data-focused=false] {
            opacity: 0;
            position: absolute;
        }
    }

    &__options {
        padding-bottom: 8px;
        border-radius: 0 0 8px 8px;
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 100%;
        background-color: rgb(252, 253, 254);
        border: 1px solid rgb(220, 225, 227);
        border-top: none;
        font-size: inherit;
        overflow-y: auto;
        z-index: 99999;

        &::-webkit-scrollbar {
            width: 4px;
        }

        &::-webkit-scrollbar-track {
            background: #ffffff00;
        }

        &::-webkit-scrollbar-thumb {
            background-color: #c7c7c7;
            border-radius: 9999px;
        }

        & > ul > li {
            border-bottom: 1px solid rgba(0, 0, 0, .1);
            padding: 4px 10px;
            cursor: pointer;

            &.searchable-select__not-found {
                opacity: .5;
                text-align: center;
                cursor: not-allowed;
            }

            //&.searchable-select__selected {
            &[data-selected=true] {
                background-color: rgba(0, 0, 0, .01);
            }

            &:hover {
                background-color: rgba(0, 0, 0, .05);
            }

            &:last-child {
                border: none;
            }
        }
    }
}
</style>
