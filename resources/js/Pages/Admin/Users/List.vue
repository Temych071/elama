<template>
    <page-title title="Пользователи"/>
    <div class="admin-users">
        <div class="flex justify-between items-center">
            <a :href="route('admin.users.export')" class="btn btn-outline-primary">
                Скачать в Excel
            </a>
            <Link
                class="btn btn-md btn-primary"
                :href="route('admin.users.create.show')"
            >
                {{ $t('admin.sidebar.newUserButton') }}
            </Link>
        </div>

        <div class="mt-4">
            <form @submit.prevent="applyFilter" class="flex">
                <select v-model="filterForm.has_active_subscriptions">
                    <option :value="null">Все</option>
                    <option value="active">С активной</option>
                    <option value="inactive">С неактивной</option>
                </select>

                <button type="submit" class="ml-4 btn btn-primary">Применить</button>
            </form>
        </div>

        <div class="">
            <div class="grid grid-cols-8 gap-x-8 items-center text-sm font-black uppercase py-4 px-8">
                <div>{{ $t('admin.users.header.name') }}</div>
                <div>{{ $t('admin.users.header.balance') }}</div>
                <div>{{ $t('admin.users.header.campaignsCount') }}</div>
                <div>{{ $t('admin.users.header.email') }}</div>
                <div>{{ $t('admin.users.header.phone') }}</div>
                <div>{{ $t('admin.users.header.registration_date') }}</div>
                <div>{{ $t('admin.users.header.last_visit_date') }}</div>
                <div>{{ $t('admin.users.header.controls') }}</div>
            </div>
            <div class="campaigns-table-items">

                <article class="px-4 py-4 mb-6 text-sm border-none card" v-for="user of users">
                    <div class="grid grid-rows-1 grid-cols-8 gap-x-8 items-center">
                        <div class="grid grid-cols-1 grid-rows-2 items-center">
                            <div class="font-black">{{ user.name }}</div>
                            <div>id{{ user.id }}</div>
                        </div>
                        <div>{{ r(user.transactions_sum_amount / 1000, 2, 'f') }} ₽</div>
                        <div>{{ user.campaigns_count }}</div>
                        <div>{{ user.email }}</div>
                        <div>{{ user.phone ? `+${user.phone}` : '-' }}</div>
                        <div>{{ formatDate(user.created_at) }}</div>
                        <div>{{ user.last_visit_date ? formatDate(user.last_visit_date) : '' }}</div>
                        <div class="grid grid-flow-row-dense grid-cols-1 gap-y-2">
                            <Link class="btn btn-sm btn-outline-primary btn-block"
                                  :href="route('admin.users.edit.show', user.id)"
                            >{{ $t('admin.users.editButton') }}</Link>
                            <form-button class="btn btn-sm btn-outline-primary btn-block"
                                         :action="route('admin.users.login', user.id)"
                                         method="POST"
                                         :confirm="`Вы действительно хотите войти как ${user.name}?`"
                            >Войти как...</form-button>
                        </div>
                    </div>
                </article>

            </div>
        </div>
    </div>
</template>

<script setup>

import {Link, useForm} from "@inertiajs/vue3";
import PageTitle from "@/Components/PageTitle.vue";
import {dateToUserTzString, r} from "@/utils";
import FormButton from "@/Components/Forms/FormButton.vue";

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
});

const filterForm = useForm({
    has_active_subscriptions: route().params.has_active_subscriptions ?? null,
});

const formatDate = (date) => {
    return dateToUserTzString(date, {
        hourCycle: 'h24',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
    });
}

const applyFilter = () => {
    filterForm.get('');
}

</script>

<script>
import Layout from '@/Layouts/Admin.vue';

export default {layout: Layout}
</script>
