<template>
    <div class="header flex flex-row justify-between">

        <div class="flex flex-row md:justify-end justify-around items-center w-full md:flex-nowrap flex-wrap">
            <nav-link :href="route('campaign.index')" class="flex-shrink-0">
                <application-logo v-if="!(currentCampaign?.id ?? null)" class="mr-12" />
            </nav-link>

            <div class="flex items-center w-full">
                <nav-link :href="route('campaign.index')" class="mr-4 flex-shrink-0">
                    <img alt="projects-list" src="/icons/research.svg" />
                </nav-link>
                <searchable-select
                    :options="[
                        {
                            name: 'Список проектов',
                            id: -1
                        },
                        ...campaigns,
                    ]"
                    input-classes="select-control"
                    :model-value="currentCampaign?.id ?? null"
                    @update:model-value="onCampaignChange"
                    placeholder="Выбрать проект"
                    value-field="id"
                    display-field="name"
                    class="w-full md:w-80"
                    :ignore-case="true"
                    item-classes="py-2"
                >
                    <template v-slot:item="{option}">
                        <a class="control campaign flex items-center pr-2 w-full">
                            <div class="flex-grow text-ellipsis overflow-hidden">{{ option.name }}</div>
                            <div class="flex flex-row flex-wrap justify-items-center items-center h-6 flex-shrink-0">
                                <SourceIcon :source-type="sourceType"
                                            class="source-icon ml-2 hover:scale-[120%] transition-all"
                                            v-for="sourceType in option?.sources ?? []"
                                            @click.stop="visitSourceSettings(option, sourceType)"
                                />
                            </div>
                        </a>
                    </template>
                </searchable-select>
            </div>

            <div class="flex-grow md:block hidden"></div>

            <div
                class="flex-shrink-0 grid grid-cols-2 gap-x-2 gap-y-0 md:grid-cols-1 px-2 md:py-0 py-4 md:text-left text-center text-gray-500 md:text-xs text-sm md:w-auto w-full"
            >
                <p>{{ account?.name ?? 'Unknown' }}</p>
                <nav-link :href="route('user.billing.new-payment.show')">
                    <span>Баланс: </span>
                    <span>{{ balance }} ₽</span>
                    <span v-if="daysLeft"> ({{ $tc('header.days_left', r(daysLeft, 0, 'f')) }})</span>
                </nav-link>
            </div>

            <a href="https://t.me/dailygrow_bot" target="_blank" class="w-10 flex-shrink-0">
                <img src="/icons/support.svg" alt="tg"/>
            </a>

            <a href="https://imtera.notion.site/imtera/DailyGrow-39af267e229f4371bc3585ad79ced900" class="w-5 mx-2.5 flex-shrink-0">
                <img src="/icons/circle-question-regular.svg" alt="help"/>
            </a>

            <dropdown width="notifications" @markAsRead="onNotifyMarkAsRead" class="flex-shrink-0">
                <template #trigger>
                    <a class="control cursor-pointer">
                        <img src="/icons/notifications.svg" alt="notifications" class="w-10"/>
                    </a>
                </template>
                <template #content>
                    <notifs-dropdown/>
                </template>
            </dropdown>

            <dropdown class="flex-shrink-0">
                <template #trigger>
                    <a class="control cursor-pointer">
                        <img src="/icons/cog.svg" alt="cog" class="w-10"/>
                    </a>
                </template>
                <template #content>
                    <div class="grid grid-cols-1 cog-dropdown w-40">
                        <!--                        <Link href="#">{{ $t('header.menu.account') }}</Link>-->
                        <!--                        <Link href="#">{{ $t('header.menu.users') }}</Link>-->
                        <Link :href="route('user.change-pass')">Смена пароля</Link>
                        <Link :href="route('campaign.settings')">{{ $t('header.menu.projectsSettings') }}</Link>
                        <Link :href="route('user.billing.new-payment.show')">{{ $t('header.menu.balance') }}</Link>
                        <Link :href="route('user.settings_notifications.show')">{{
                                $t('header.menu.notifications')
                            }}
                        </Link>
                        <!--                        <Link href="#">{{ $t('header.menu.integration') }}</Link>-->
                    </div>
                </template>
            </dropdown>

            <nav-link as="button" :href="route('logout')" method="post" class="flex-shrink-0">
                <img src="/icons/logout.svg" alt="logout" class="w-10"/>
            </nav-link>
        </div>
    </div>
</template>

<script>
import Dropdown from "@/Components/Dropdown.vue";
import NavLink from "./NavLink.vue";
import NotifsDropdown from "@/Components/NotifsDropdown.vue";
import {Link} from "@inertiajs/vue3";
import SourceIcon from "@/Pages/Sources/Components/SourceIcon.vue";
import {n, isEmpty, r, isEmpty2} from "@/utils";
import axios from "axios";
import { router } from '@inertiajs/vue3'
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

export default {
    name: "AppHeader",
    components: {ApplicationLogo, SearchableSelect, NotifsDropdown, Dropdown, NavLink, Link, SourceIcon},
    props: {
        isCollapsed: {
            type: Boolean,
            required: true
        },
    },

    data() {
        return {
            daysLeft: null,
        };
    },

    mounted() {
        axios.get(route('user.billing.days-left.get')).then(({data}) => {
            this.daysLeft = Number(data);
        });
    },

    methods: {
        isEmpty, r,

        onNotifyMarkAsRead() {
            axios.post(this.route('read_notifications')).then(
                () => router.reload({only: ['notifications']})
            );
        },

        visitSourceSettings(campaign, sourceType) {
            const url = route(`campaign.source.settings.${sourceType}.show`, {campaign: campaign});
            this.$inertia.visit(url);
        },

        getSelectedCamp() {
            return n(
                this.$page.props?.activeCampaignId
                ?? this.$page.props?.campaignId
                ?? this.$page.props?.campaign?.id
                ?? this.route()?.params?.campaign
            );
        },

        onCampaignChange(cId) {
            if (!isEmpty2(cId)) {
                if (cId > 0) {
                    router.visit(route('campaign.redirect', {campaign: cId}));
                } else {
                    router.visit(route('campaign.index'));
                }
            }
        },
    },

    computed: {
        account() {
            return this.$page.props.auth?.user;
        },
        balance() {
            return r((this.$page.props.auth?.balance ?? 0) / 1000, 2);
        },
        campaigns() {
            return this.$page.props.campaigns;
        },
        currentCampaign() {
            let selId = this.getSelectedCamp();
            for (let i in this.campaigns) {
                if (this.campaigns[i].id === selId) {
                    return this.campaigns[i];
                }
            }
            return null;
            // return this.campaigns
            // return this.$page.props.campaign ?? this.$page.props.campaigns[0] ?? null;
        }
    }
}
</script>
