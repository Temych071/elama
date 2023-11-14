<template>
    <div class="admin-billing-settings">
        <h1 class="text-xl font-bold mb-4">Настройки биллинга</h1>
        <form @submit.prevent="onSubmit">
            <validation-errors/>

            <div class="form-field">
                <label class="form-label">Стартовый баланс</label>
                <input type="number"
                       class="form-control"
                       @input="form.trial_balance = $event.target.value * 1000"
                       :value="form.trial_balance / 1000"
                       min="0"
                       required />
            </div>

            <div class="form-field mt-4">
                <label class="form-label">Тариф для новых проектов</label>
                <select v-model.number="form.initial_plan_id" class="form-select">
                    <option :value="null">Не применять</option>
                    <option v-for="plan in plans"
                            :key="plan.id"
                            :value="plan.id"
                    >{{ plan.name }} ({{ plan.id }})</option>
                </select>
            </div>

            <button type="submit" class="btn btn-md btn-primary mt-4">Сохранить</button>
        </form>
    </div>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const props = defineProps({
    settings: {
        type: Number,
        required: true,
    },
    plans: {
        type: Array,
        required: false,
        default: [],
    },
});

const form = useForm(props.settings);

function onSubmit() {
    form.put(route('admin.billing.settings.store'));
}

</script>

<script>
import layout from "@/Layouts/Admin.vue";

export default {layout};
</script>
