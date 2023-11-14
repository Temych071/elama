<template>
    <div class="sidebar">
        <div v-if="!isCollapsed" class="w-full py-4 px-4">
            <p class="font-bold">{{ project.name }}</p>
            <p class="text-xs">Тариф: {{ project.active_subscription?.plan?.name ?? 'Не выбран' }}</p>
        </div>

        <div class="campaign-menu">
            <!--suppress RequiredAttributes -->
            <sidebar-menu-item
                v-for="item in menuItems"
                v-bind="item"
                :is-collapsed="isCollapsed"
                :project-id="project.id"
            />
        </div>
    </div>
</template>

<script setup>
import SidebarMenuItem from "@/Components/NavMenu/SidebarMenuItem.vue";
import {computed} from "vue";
import {useI18n} from "vue-i18n";

const props = defineProps({
    isCollapsed: {
        type: Boolean,
        required: false,
        default: false,
    },
    project: {
        type: Object,
        required: true,
    },
});

const t = useI18n().t;

const campMenu = [
    {
        title: t('sidebar.menu.analytics'),
        route: 'campaign.browse',
        checkRoute: 'campaign.browse.*',
        forCampaign: true,
        icon: 'analytics',
    },
    {
        title: 'Отзывы',
        route: 'reviews.private.forms',
        forCampaign: true,
        icon: 'star',
        nested: [
            {
                title: 'Филиалы',
                route: 'reviews.private.stats.first',
                checkRoute: 'reviews.private.stats.*',
                forCampaign: true,
            },
            {
                title: 'Отзывы',
                route: 'reviews.private.list',
                forCampaign: true,
            },
            {
                title: 'Настройка',
                route: 'reviews.private.forms',
                forCampaign: true,
                checkRoute: 'reviews.private.forms.*',
            },
        ],
    },
    {
        title: 'Виджеты',
        route: 'social-widget.private.index',
        checkRoute: 'social-widget.private.*',
        forCampaign: true,
        // forAdmins: true,
        icon: 'support',
    },
    {
        title: 'Программа лояльности',
        route: 'loyalty.private.loyalty.index',
        checkRoute: 'loyalty.private.loyalty.*',
        forCampaign: true,
        icon: 'star',
        forAdmins: true,
        visible: false,
    },
    {
        title: t('sidebar.menu.settings'),
        route: 'campaign.source',
        forCampaign: true,
        icon: 'tools',
    },
    // {
    //     title: 'SEO',
    //     route: 'seo-audit.index',
    //     forCampaign: false,
    //     visible: false,
    //     icon: 'tools',
    // },
    // {
    //     title: this.$t('sidebar.menu.help'),
    //     route: 'help',
    //     forCampaign: false,
    //     icon: 'support',
    // },
    {
        title: 'База знаний',
        href: 'https://imtera.notion.site/imtera/DailyGrow-39af267e229f4371bc3585ad79ced900',
        forCampaign: false,
        icon: 'support',
        target: '_blank'
    },
];

const menuItems = computed(() => campMenu.filter((item) => item.visible ?? true));
</script>
