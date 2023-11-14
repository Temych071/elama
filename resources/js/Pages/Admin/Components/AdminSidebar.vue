<template>
    <div class="sidebar">

        <div class="admin-menu">
            <Link
                v-for="item in adminMenuItems"
                :href="getMenuLink(item.route)"

                class="campaign-menu-item"
                :class="{ active: isMenuItemActive(item.route) }"
            >{{ item.title }}
            </Link>
        </div>
    </div>

</template>

<script>
import SourceIcon from "@/Pages/Sources/Components/SourceIcon.vue";
import {Link} from '@inertiajs/vue3';

export default {
    name: "AdminSidebar",
    components: {Link, SourceIcon},
    data() {
        return {
            adminMenuItems: [
                {title: 'Проекты', route: 'admin.campaigns'},
                {title: 'Заявки на настройку', route: 'admin.settingsRequests'},
                // {title: 'Статистика', route: 'admin.statistics'},
                {title: 'Пользователи', route: 'admin.users.list'},
                {title: 'Мониторинг', route: 'horizon.index'},
                {title: 'Тарифы', route: 'admin.plans.list'},
                {title: 'Транзакции', route: 'admin.transactions.list'},
                {title: 'Промокоды', route: 'admin.discount-codes.list'},
                {title: 'Счета на оплату', route: 'admin.invoices.list'},
                {title: 'Настройки', route: 'admin.billing.settings.show'},
            ],
        };
    },
    computed: {
        account() {
            return this.$page.props.auth?.user;
        },
    },

    methods: {
        isMenuItemActive(route) {
            return this.getMenuLink(route).endsWith(this.$page.url.split('?')[0]);
        },

        getMenuLink(route) {
            return this.route(route);
        },
    },
}
</script>
