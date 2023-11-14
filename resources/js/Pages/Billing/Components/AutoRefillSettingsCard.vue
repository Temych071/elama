<template>
	<div class="py-4 px-8 w-full h-full relative">
        <loading-block
            spinner-dark
            v-if="!isLoaded"
            class="bg-white"
        />

        <loading-block
            spinner-dark
            v-if="form.processing"
            class="bg-gray-500/10"
        />

        <h2 class="text-xl mb-2">Настройка автоматического пополнения</h2>

        <form @submit.prevent="onSubmit">
            <toggle-switch v-model:checked="form.enabled">Включить</toggle-switch>

            <div class="relative">
                <div
                    v-if="!form.enabled"
                    class="absolute -top-2 -left-2 -bottom-2 -right-2 bg-gray-500/10 rounded-md"
                ></div>

                <label class="text-sm block my-2">
                    Пополнять баланс на, ₽
                    <input
                        type="number"
                        v-model.number="form.amount"
                        class="form-control-sm w-full"
                        min="1"
                    >
                    <input-error :message="error('amount')" />
                </label>

                <label class="text-sm block my-2">
                    Если баланс меньше, ₽
                    <input
                        type="number"
                        v-model.number="form.limit"
                        class="form-control-sm w-full"
                        min="1"
                    >
                    <input-error :message="error('limit')" />
                </label>

                <div class="my-2">
                    <label class="text-md block">
                        Способ оплаты
                    </label>
                    <div
                        v-if="!isEmpty2(paymentMethods)"
                        class="space-y-1"
                    >
                        <label
                            v-for="paymentMethod in paymentMethods"
                            :key="paymentMethod.id"
                            class="space-x-1 flex flex-row items-center"
                            :class="`${paymentMethod.status === 'available' ? 'cursor-pointer' : 'line-through'}`"
                        >
                            <input
                                type="radio"
                                v-model.number="form.payment_method_id"
                                :value="paymentMethod.id"
                                :disabled="paymentMethod.status !== 'available'"
                            >
                            <span
                                class="text-sm"
                                :title="`${paymentMethod.status !== 'available' ? 'Способ оплаты недоступен. Удалите его и попробуйте добавить снова.' : ''}`"
                            >{{ paymentMethod.name }} <span class="text-xs text-gray-500">({{ dateToUserTzString(paymentMethod.created_at) }})</span></span>
                            <img alt="delete"
                                 src="/icons/delete.svg"
                                 title="Отвязать способ оплаты"
                                 class="cursor-pointer h-3 w-3"
                                 @click.prevent="onPaymentMethodDelete(paymentMethod)"
                            >
                        </label>
                    </div>
                    <div v-else class="text-sm text-gray-500">
                        Вы не сохранили ни одного способа оплаты...
                    </div>
                    <input-error :message="error('payment_method_id')" />
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary mt-2"
                        @click="onAddNewMethod"
                    >Привязать новую карту</button>
                </div>
            </div>

            <button
                type="submit"
                class="btn btn-md btn-primary w-full mt-4"
            >Сохранить</button>
        </form>
	</div>
</template>

<script setup>
import {onBeforeMount, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import {dateToUserTzString, isEmpty2} from "@/utils.js";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import InputError from "@/Components/InputError.vue";
import {isArray} from "lodash";

const form = useForm({
    enabled: true,
    amount: 5000,
    limit: 1000,
    payment_method_id: null,
});

const isLoaded = ref(false);

async function getSettings() {
    return (await axios.get(route('user.billing.auto-refill-settings.get'))).data
}

async function getPaymentMethods() {
    return paymentMethods.value = (await axios.get(route('user.billing.auto-refill-settings.get-payment-methods'))).data;
}

async function updateData(resetForm = true) {
    paymentMethods.value = await getPaymentMethods();
    form.defaults(await getSettings());
    if (resetForm) {
        form.reset();
    }
    form.clearErrors();
}

const paymentMethods = ref([]);

onBeforeMount(async () => {
    await updateData();
    isLoaded.value = true;
});

function onAddNewMethod() {
    form.processing = true;
    axios.post(route('user.billing.new-payment.create', {
        amount: 1,
        savePaymentMethod: true,
    })).then(({data}) => {
        location.href = data.confirmation_url;
    }).catch(() => {
        form.processing = false;
        page.props.toast = {
            type: 'error',
            message: 'Не удалось создать счёт на оплату...',
        };
    });
}

function onSubmit() {
    form.processing = true;
    form.clearErrors();

    axios.put(route('user.billing.auto-refill-settings.save'), form.data()).then(res => {
        form.defaults(res.data);
        form.reset();
    }).catch(error => {
        form.errors = error.response.data.errors;
    }).finally(() => {
        form.processing = false;
    });
}

async function onPaymentMethodDelete(paymentMethod) {
    if (!confirm(`Вы действительно хотите удалить способ оплаты '${paymentMethod.name}'?`)) {
        return;
    }

    form.processing = true;
    await axios.delete(route('user.billing.auto-refill-settings.delete-payment-method', {
        payment_method_id: paymentMethod.id,
    }));

    await updateData(false);
    console.log(form.payment_method_id, paymentMethod.id);
    if (form.payment_method_id === paymentMethod.id) {
        form.reset(['payment_method_id']);
    }

    form.processing = false;

    console.log(form.data());
}

function error(field) {
    if (isEmpty2(form.errors[field])) {
        return null;
    }

    if (isArray(form.errors[field])) {
        return form.errors[field][0] ?? null;
    }

    return form.errors[field];
}

</script>
