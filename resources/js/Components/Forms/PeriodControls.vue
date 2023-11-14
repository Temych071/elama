<template>
    <div class="period-controls">
        <!-- Full Screen Dropdown Overlay -->
        <div
            v-show="open"
            class="fixed inset-0 z-40"
            @click="open = false"
        />
        <dropdown ref="dateRangePicker"
                  align="left"
                  :manual="true"
                  width="260"
                  class="period-controls-dropdown-container"
        >

            <template #trigger>
                <form-select
                    :options="dropdownItems"
                    :active-item="currentPeriodStr"
                    class="period-dropdown min-w-[12rem]"
                />
            </template>

            <template #content>
                <div class="p-2">
                    <v-date-picker
                        is-range
                        mode="date"
                        :min-date="minDate ?? undefined"
                        :max-date="new Date()"
                        v-model="selectedRangeRaw"
                        :locale="$utils.getLocale()"
                    />
                    <form-button
                        class="btn btn-primary btn-block btn-md mt-2"
                        :disabled="!selectedRange"
                        :data="getForm(selectedRange)"
                        @click="closeDateRangePicker"
                    >{{ $t('period.picker.select') }}
                    </form-button>
                </div>
            </template>
        </dropdown>

        <form-button
            v-for="btn in p_buttons"
            :data="getForm(btn.value)"
            class="period-control period-button"
        >{{ btn.title }}
        </form-button>

        <slot/>
    </div>
</template>

<script>
import FormButton from '@/Components/Forms/FormButton.vue';
import FormSelect from "@/Components/Forms/FormSelect.vue";
import Dropdown from "@/Components/Dropdown.vue";

export default {
    name: "PeriodControls",
    components: {
        Dropdown,
        FormSelect,
        FormButton,
    },

    props: {
        name: {
            type: String,
            required: false,
            default: 'period',
        },
        currentPeriodStr: {
            type: String,
            required: false,
            default: '---',
        },
        buttons: {
            type: [Array, null],
            required: false,
            default: null,
        },
        ddItems: {
            type: [Array, null],
            required: false,
            default: null,
        },
        customPeriodTitle: {
            type: [null, String],
            required: false,
            default: undefined,
        },
        minDate: {
            required: false,
            type: Date,
            default: null
        },
        dontSendForm: {
            required: false,
            type: Boolean,
            default: false,
        },
    },

    emits: ['change'],

    beforeMount() {
        this.p_buttons = this.buttons ?? [
            {title: this.$t('period.picker.today'), value: 'today'},
            {title: this.$t('period.picker.yesterday'), value: 'yesterday'},
            {title: this.$t('period.picker.7days'), value: '7days'},
            {title: this.$t('period.picker.30days'), value: '30days'},
            {title: this.$t('period.picker.month'), value: 'month'},
            {title: this.$t('period.picker.90days'), value: '90days'},
        ];

        this.p_ddItems = this.ddItems ?? [
            {title: this.$t('period.picker.yesterday'), value: 'yesterday'},
            {title: this.$t('period.picker.last-week'), value: 'last-week'},
            {title: this.$t('period.picker.7days'), value: '7days'},
            {title: this.$t('period.picker.month'), value: 'month'},
            {title: this.$t('period.picker.last-month'), value: 'last-month'},
            {title: this.$t('period.picker.year'), value: 'year'},
        ];

        this.p_customPeriodTitle = this.customPeriodTitle ?? this.$t('period.picker.customPeriod');
    },

    data() {
        return {
            selectedRangeRaw: null,
            p_buttons: null,
            p_ddItems: null,
            p_customPeriodTitle: null,
            open: this.$refs.dateRangePicker?.open ?? false,
        };
    },

    watch: {
        open(value) {
            if (this.$refs.dateRangePicker) {
                this.$refs.dateRangePicker.open = value;
            }
        }
    },

    computed: {
        selectedRange() {
            if (!this.selectedRangeRaw) {
                return null;
            }

            let start = this.selectedRangeRaw.start.toISOString().split('T')[0];
            let end = this.selectedRangeRaw.end.toISOString().split('T')[0];

            return `${start}:${end}`;
        },

        dropdownItems() {
            let items = this.p_ddItems.map((item) => {
                return {
                    name: item.title,
                    data: this.getForm(item.value),
                };
            });
            items.push({
                name: this.p_customPeriodTitle,
                data: this.openDateRangePicker,
            });

            return items;
        },
    },

    methods: {
        openDateRangePicker() {
            this.open = true;
            return null;
        },

        closeDateRangePicker() {
            this.open = false;
        },

        closeOnEscape(e) {
            if (this.open && e.keyCode === 27) {
                this.open = false;
            }
        },

        getForm(value) {
            if (this.dontSendForm) {
                return () => this.$emit('change', value);
            }

            let form = {};
            form[this.name] = value;
            return form;
        },
    },

    mounted() {
        document.addEventListener('keydown', this.closeOnEscape);
    },

    unmounted() {
        document.removeEventListener('keydown', this.closeOnEscape)
    }
}
</script>
