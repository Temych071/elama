<template>
    <div>
        <div class="mb-4">
            <nav-link :href="route('admin.discount-codes.create')"
                      class="btn btn-md btn-primary"
            >Добавить</nav-link>
        </div>
        <div>
            <table class="w-full bg-white">
                <tr class="text-left">
                    <th class="border border-slate-300 px-2 py-1">Код</th>
                    <th class="border border-slate-300 px-2 py-1">Скидка</th>
                    <th class="border border-slate-300 px-2 py-1">Одноразовый</th>
                    <th class="border border-slate-300 px-2 py-1">Активен</th>
                    <th class="border border-slate-300 px-2 py-1">Активирован</th>
                    <th class="border border-slate-300 px-2 py-1">Действия</th>
                </tr>

                <tr v-for="code in codes">
                    <td class="border border-slate-300 p-2">{{ code.code }}</td>
                    <td class="border border-slate-300 p-2">
                        <span v-if="code.amount">{{ (+code.amount) / 1000.0 }} руб</span>&nbsp;
                        <span v-if="code.percent">{{ code.percent }}%</span>
                    </td>
                    <td class="border border-slate-300 p-2">{{ code.is_one_time ? 'Да' : 'Нет' }}</td>
                    <td class="border border-slate-300 p-2">{{ code.is_active ? 'Да' : 'Нет' }}</td>
                    <td class="border border-slate-300 p-2">{{ code.used_at ? dateFormat(code.used_at) : '-' }}</td>
                    <td class="border border-slate-300 p-2">
                        <nav-link class="btn btn-sm btn-primary w-full"
                                  :href="route('admin.discount-codes.edit', {code_id: code.id})"
                        >Изменить</nav-link>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import Layout from '@/Layouts/Admin.vue';
import TextInput from "@/Components/Forms/TextInput.vue";
import FormSelect from "@/Components/Forms/FormSelect.vue";
import FormSearch from "@/Components/Forms/FormSearch.vue";
import NavLink from "@/Components/NavLink.vue";
import {dateFormat} from '@/utils';

export default {
    components: {NavLink, FormSearch, FormSelect, TextInput},
    layout: Layout,
    props: ['codes'],
    methods: {dateFormat},
}
</script>
