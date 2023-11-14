<template>
    <form class="card-md p-8" @submit.prevent="onSubmit">
        <h2 class="card-title-d">Порядок показателей</h2>
        <div class="form-field py-2" v-for="(orderItem, i) in form.order" :key="orderItem.num">
            <span class="font-black text-lg p-4">{{ orderItem.num }}</span>
            <select v-model="form.order[i].field"
                    @change="onChange(i)"
                    class="form-select"
                    style="display: inline-block"
            >
                <option v-for="field in fieldsList"
                        :key="field"
                        :value="field"
                        :disabled="field === form.order[i].field"
                >
                    {{ field ? $t('campaigns.planfact.fields.' + field) : 'Пусто' }}
                </option>
            </select>
        </div>

        <validation-errors/>

        <button class="mt-4 btn btn-md btn-primary">Применить</button>
    </form>
</template>

<script setup>
import {computed, onBeforeMount} from "vue";
import {useForm} from '@inertiajs/vue3';
import ValidationErrors from "@/Components/ValidationErrors.vue";
import {n} from '@/utils';

const props = defineProps({
    values: {
        type: Array,
        required: false,
        default: [],
    },
    fields: {
        type: Array,
        required: false,
        default: ['expenses', 'income', 'clicks', 'requests', 'cpc', 'cpl', 'cr', 'drr'],
    },
    href: {
        type: String,
        required: true,
    },
});

const form = useForm({
    order: [],
});

const fieldsList = computed(() => {
    return [null, ...props.fields];
});

const fieldsCount = computed(() => props.fields?.length ?? 0);

function onChange(selIndex) {
    let selField = form.order[selIndex].field;
    if (selField === null) {
        return;
    }

    for (let i in form.order) {
        if (n(i) !== n(selIndex) && form.order[i].field === selField) {
            form.order[i].field = null;
        }
    }
}

onBeforeMount(() => {
    form.order = props.values.sort((a, b) => {
        if (a.num < b.num) {
            return -1;
        }
        return a.num > b.num;
    });

    if (!form.order.length) {
        form.order = props.fields.map((field, num) => {
            return {num: num + 1, field};
        });
    }

    for (let i = 0; i < fieldsCount.value; i++) {
        form.order[i] = form.order[i] ?? {
            num: i + 1,
            field: null,
        };
    }
});

const onSubmit = () => form.put(props.href);

</script>
