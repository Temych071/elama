<template>
    <div class="admin-transactions">
        <form @submit.prevent="onSubmit">
            <validation-errors/>

            <table class="w-full bg-white">
                <tr class="text-left">
                    <th class="border border-slate-300 px-2 py-1">id</th>
                    <th class="border border-slate-300 px-2 py-1">Пользователь</th>
                    <th class="border border-slate-300 px-2 py-1">Тип</th>
                    <th class="border border-slate-300 px-2 py-1">Сумма</th>
                    <th class="border border-slate-300 px-2 py-1">Дата</th>
                </tr>

                <tr>
                    <td class="border border-slate-300 px-2">-</td>
                    <td class="border border-slate-300 px-2">
                        <searchable-select
                            :options="users"
                            placeholder="Выберите пользователя"
                            v-model="addForm.user_id"
                            value-field="id"
                            :display-callback="(user) => `${user.email} (${user.id})`"
                            class="w-full h-full text-sm"
                            input-classes="select-sm"
                        />
                    </td>
                    <td class="border border-slate-300 px-2">
                        <searchable-select
                            :options="transactionTypes"
                            placeholder="Выберите тип транзакции"
                            v-model="addForm.type"
                            :display-callback="translateType"
                            class="w-full h-full text-sm"
                            input-classes="select-sm"
                        />
                    </td>
                    <td class="border border-slate-300">
                        <input class="w-full px-2 py-1 border-0" required v-model.number="addForm.formatted_amount"
                               type="number" step="0.01"/>
                    </td>
                    <td class="border border-slate-300 px-2">
                        <button class="btn btn-sm btn-primary w-full">
                            Создать
                        </button>
                    </td>
                </tr>

                <tr v-for="transaction in transactions.data">
                    <td class="border border-slate-300 p-2">{{ transaction.id }}</td>
                    <td class="border border-slate-300 p-2">{{ transaction.user?.email ?? '' }} ({{
                            transaction.user?.id ?? ''
                        }})
                    </td>
                    <td class="border border-slate-300 p-2">
                        {{ translateType(transaction.type) }}
                        <span v-if="transaction.description">({{ transaction.description }})</span>
                    </td>
                    <td class="border border-slate-300 p-2">{{ (transaction.formatted_amount) }}</td>
                    <td class="border border-slate-300 p-2">
                        {{ formatDate(transaction.created_at) }}
                    </td>
                </tr>
            </table>
        </form>

        <div class="mt-6 flex justify-end">
            <NavLink
                v-if="transactions.prev_page_url"
                :href="transactions.prev_page_url"
                class="btn btn-md btn-outline-black mr-2"
            >
                Назад ({{ transactions.current_page - 1 }})
            </NavLink>
            <NavLink
                v-if="transactions.next_page_url"
                :href="transactions.next_page_url"
                class="btn btn-md btn-outline-black"
            >
                Вперед ({{ transactions.current_page + 1 }})
            </NavLink>
        </div>
    </div>
</template>

<script>
import Layout from '@/Layouts/Admin.vue';
import TextInput from "@/Components/Forms/TextInput.vue";
import FormSelect from "@/Components/Forms/FormSelect.vue";
import FormSearch from "@/Components/Forms/FormSearch.vue";
import NavLink from "@/Components/NavLink.vue";
import {useForm} from "@inertiajs/vue3";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import {dateToUserTzString} from "@/utils";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";

export default {
    components: {SearchableSelect, ValidationErrors, NavLink, FormSearch, FormSelect, TextInput},
    layout: Layout,
    props: ['transactions', 'users', 'transactionTypes'],
    data() {
        return {
            addForm: useForm({
                user_id: null,
                type: null,
                formatted_amount: 0,
            }),
            test: null,
        };
    },
    methods: {
        onSubmit() {
            this.addForm.post(route('admin.transactions.create'));
            this.addForm.reset();
        },
        translateType(type) {
            if (type === 'refill_from_card') return 'Пополнение с карты';
            if (type === 'subscription_charge') return 'Списание за подписку';
            if (type === 'refill_by_invoice') return 'Пополнение через расчётный счёт';
            if (type === 'discount_code_percent') return 'Промокод (Процент)';
            if (type === 'discount_code_amount') return 'Промокод (Сумма)';
            if (type === 'trial_balance') return 'Стартовый баланс';
            return type;
        },
        formatDate(date) {
            return dateToUserTzString(date);
        }
    },
}
</script>
