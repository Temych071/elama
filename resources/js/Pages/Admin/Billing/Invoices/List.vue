<template>
    <div class="admin-invoices">

        <div>
            <validation-errors/>
            <table class="w-full bg-white">
                <thead>
                <tr>
                    <th class="border border-slate-300 px-2 py-1">#</th>
                    <th class="border border-slate-300 px-2 py-1">Телефон</th>
                    <th class="border border-slate-300 px-2 py-1">E-Mail</th>
                    <th class="border border-slate-300 px-2 py-1">Название компании/ИНН</th>
                    <th class="border border-slate-300 px-2 py-1">Сумма</th>
                    <th class="border border-slate-300 px-2 py-1">Дата</th>
                    <th class="border border-slate-300 px-2 py-1">Промокод</th>
                    <th class="border border-slate-300 px-2 py-1"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="invoice in invoices.data">
                    <td class="border border-slate-300 px-2">{{ invoice.id }}</td>
                    <td class="border border-slate-300 px-2">
                        <a class="link" :href="`tel:${invoice.phone}`">+{{ invoice.phone }}</a>
                    </td>
                    <td class="border border-slate-300 px-2">
                        <a class="link" :href="`mailto:${invoice.email}`">{{ invoice.email }}</a>
                    </td>
                    <td class="border border-slate-300 px-2">{{ invoice.company_name }}</td>
                    <td class="border border-slate-300 px-2">{{ invoice.formatted_amount }} ₽</td>
                    <td class="border border-slate-300 p-2">
                        {{ dateToUserTzString(invoice.created_at) }}
                    </td>
                    <td class="border border-slate-300 p-2">
                        {{ invoice?.discount_code?.code ?? '-' }}
                    </td>
                    <td class="border border-slate-300 px-2">
                        <form-button class="btn btn-sm btn-success w-full"
                                     v-if="isEmpty(invoice.transaction_id)"
                                     method="PUT"
                                     :on-prepare="onPrepareConfirm"
                                     :action="route('admin.invoices.confirm')"
                                     :data="{
                                         formatted_amount: invoice.formatted_amount,
                                         invoice_id: invoice.id,
                                     }"
                        >Подтвердить</form-button>
                        <button class="btn btn-sm btn-success w-full" disabled v-else>Подтверждено</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <NavLink
                v-if="invoices.prev_page_url"
                :href="invoices.prev_page_url"
                class="btn btn-md btn-outline-black mr-2"
            >
                Назад ({{ invoices.current_page - 1 }})
            </NavLink>
            <NavLink
                v-if="invoices.next_page_url"
                :href="invoices.next_page_url"
                class="btn btn-md btn-outline-black"
            >
                Вперед ({{ invoices.current_page + 1 }})
            </NavLink>
        </div>
    </div>
</template>

<script setup>
import NavLink from "@/Components/NavLink.vue";
import {isEmpty, dateToUserTzString} from "@/utils";
import FormButton from "@/Components/Forms/FormButton.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const props = defineProps({
    invoices: {
        type: Object,
        required: true,
    },
});

function onPrepareConfirm(data) {
    let formatted_amount = prompt('Сумма пополнения:', data.formatted_amount ?? 0);
    return {formatted_amount};
}
</script>

<script>
import layout from "@/Layouts/Admin.vue";

export default {layout};
</script>
