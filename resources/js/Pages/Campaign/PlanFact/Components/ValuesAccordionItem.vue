<template>
    <div class="accordion-item">
        <div class="accordion-item-header" @click="isOpen = !isOpen">
            {{ fmtMonth(values.month) }}

            <div v-if="buttons !== null" class="float-right">
                <button v-if="buttons.copy"
                        @click.stop="buttons.copy(values)"
                        type="button"
                        class="p-0.5 mr-1"
                        :title="$t('campaigns.planfact.settings.add.copyPlanToNextMonth')"
                >
                    <img src="/icons/copy.svg" alt="copy"/>
                </button>
                <button v-if="buttons.del"
                        @click.stop="buttons.del"
                        type="button"
                        class="p-0.5"
                >
                    <img src="/icons/delete.svg" alt="delete"/>
                </button>
            </div>
        </div>

        <div class="accordion-item-body" v-if="isOpen">
            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.fields.expenses') }}</label>
                <input
                    v-model.number="expenses"
                    type="number"
                    min="0"
                    :placeholder="$t('campaigns.planfact.settings.add.fieldPlaceholder')"
                    class="form-control"
                />
            </div>

            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.fields.clicks') }}</label>
                <input
                    v-model.number="clicks"
                    type="number"
                    min="0"
                    class="form-control"
                />
            </div>

            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.fields.requests') }}</label>
                <input
                    v-model.number="requests"
                    type="number"
                    min="0"
                    :placeholder="$t('campaigns.planfact.settings.add.fieldPlaceholder')"
                    class="form-control"
                />
            </div>

            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.fields.cpc') }}</label>
                <input
                    v-model.number="cpc"
                    type="number"
                    step="0.01"
                    min="0"
                    :placeholder="$t('campaigns.planfact.settings.add.fieldPlaceholder')"
                    class="form-control"
                />
            </div>

            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.fields.cr') }}</label>
                <input
                    v-model.number="cr"
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-control"
                    disabled
                />
            </div>

            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.fields.cpl') }}</label>
                <input
                    v-model.number="cpl"
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-control"
                    disabled
                />
            </div>

            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.fields.income') }}</label>
                <input
                    v-model.number="income"
                    type="number"
                    min="0"
                    :placeholder="$t('campaigns.planfact.settings.add.fieldPlaceholder')"
                    class="form-control"
                />
            </div>

            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.fields.drr') }}</label>
                <input
                    v-model.number="values.drr"
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-control"
                    disabled
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {getLocale, r} from "@/utils";
import {capitalize} from "lodash";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
    buttons: {
        type: [Object, null],
        required: false,
        default: null,
    },
});

const requests = computed({
    get: () => values.value?.requests,
    set: (v) => setHandler('requests', v),
});

const income = computed({
    get: () => values.value?.income,
    set: (v) => setHandler('income', v),
});

const cr = computed({
    get: () => values.value?.cr,
    set: (v) => setHandler('cr', v),
});

const cpl = computed({
    get: () => values.value?.cpl,
    set: (v) => setHandler('cpl', v),
});

const cpc = computed({
    get: () => values.value?.cpc,
    set: (v) => setHandler('cpc', v),
});

const clicks = computed({
    get: () => values.value?.clicks,
    set: (v) => setHandler('clicks', v),
});

const expenses = computed({
    get: () => values.value?.expenses,
    set: (v) => setHandler('expenses', v),
});

const isOpen = ref(false);

const emit = defineEmits(['update:modelValue']);

const values = computed({
    get: () => props.modelValue,
    set: (v) => emit('update:modelValue', v),
});

const FIELDS_CALC = {
    'income': ['drr'],
    'expenses': ['cpc', 'clicks', 'drr', 'cpl'],

    'clicks': ['cpc', 'cr'],
    'cpc': ['clicks', 'cr'],

    'cpl': ['requests', 'cr'],
    'requests': ['cpl', 'cr'],
};

function setHandler(field, val) {
    let vals = {...values.value};
    vals[field] = val;

    values.value = calcValues(FIELDS_CALC[field] ?? [], vals);
}

function calcValues(fields, vals) {
    if (vals === null) {
        vals = {...values.value};
    }

    if (!(fields instanceof Array)) {
        fields = [fields];
    }

    for (let i in fields) {
        switch (fields[i]) {
            case 'requests': {
                if (vals.requests && vals.clicks) {
                    vals.requests = r(vals.cr * vals.clicks, 0);
                }
                break;
            }
            case 'cr': {
                if (vals.requests && vals.clicks) {
                    vals.cr = r((vals.requests / vals.clicks) * 100, 2);
                }
                break;
            }
            case 'cpl': {
                if (vals.requests && vals.expenses) {
                    vals.cpl = r(vals.expenses / vals.requests, 2);
                }
                break;
            }
            case 'cpc': {
                if (vals.clicks && vals.expenses) {
                    vals.cpc = r(vals.expenses / vals.clicks, 2);
                }
                break;
            }
            case 'clicks': {
                if (vals.cpc && vals.expenses) {
                    vals.clicks = r(vals.expenses / vals.cpc, 0);
                }
                break;
            }
            case 'income': {
                if (vals.drr && vals.expenses) {
                    vals.income = r(vals.expenses / vals.drr, 0);
                }
                break;
            }
            case 'drr': {
                if (vals.income && vals.expenses) {
                    vals.drr = r((vals.expenses / vals.income) * 100, 1);
                }
                break;
            }
        }
    }

    return vals;
}

function fmtMonth(date) {
    return capitalize((new Date(date)).toLocaleDateString(getLocale(), {year: 'numeric', month: 'long', timeZone: 'UTC'}));
}

onMounted(() => {
    if (props.modelValue.__autoopen) {
        isOpen.value = true;
    }
});
</script>
