<template>

    <div class="py-1" v-if="isUnavailable">

        <div class="w-full flex flex-row justify-between items-center mb-6">
            <h3 class="font-bold text-sm flex-shrink-0">{{ $t('campaigns.planfact.block.title') }}</h3>

            <div v-if="false" class="flex flex-row items-center flex-shrink-0">
                <Link :href="route('campaign.project_settings', campaignId)">
                    <button class="btn btn-sm btn-outline-primary m-1">{{
                            $t('campaigns.planfact.block.settings')
                        }}
                    </button>
                </Link>
            </div>
        </div>

        <div class="w-full flex justify-center" v-if="isLoading">
            <loading-spinner dark/>
        </div>
        <div v-else class="text-center flex flex-col items-center mb-6">
            <div>{{ $t('campaigns.planfact.block.notSpecified') }}</div>

            <Link :href="routeToAdd">
                <button class="btn btn-md btn-primary mx-3 mt-3">{{ $t('campaigns.planfact.settings.btnAdd') }}</button>
            </Link>
        </div>
    </div>

    <div class="py-1" v-else>
        <div class="w-full flex md:flex-row flex-col justify-between items-center mb-6">
            <h3 class="font-bold text-sm flex-shrink-0">
                {{ $t('campaigns.planfact.block.title') }}
            </h3>

            <div class="flex flex-shrink-0 justify-self-start md:flex-row flex-col items-start w-full md:w-auto">
                <div class="px-2">
                    <label class="text-xs text-black text-opacity-70">
                        {{ $t('campaigns.planfact.block.planLabel') }}
                    </label>
                    <searchable-select
                        v-model="filters.plan_id"
                        :options="data.plans.map((plan) => {return {
                            title: plan.name,
                            value: plan.id,
                        }})"
                        input-classes="select-sm"
                        class="text-sm text-gray-500"
                        :disabled="isLoading"
                    />
                </div>

                <div class="px-2" v-if="isEmpty(data.selectedPlan?.domain)">
                    <label class="text-xs text-black text-opacity-70">
                        {{ $t('campaigns.planfact.block.domainLabel') }}
                    </label>
                    <searchable-select
                        v-model="filters.domain"
                        :options="[
                            {
                                title: $t('campaigns.planfact.block.domainAll'),
                                value: null,
                            },
                            ...data.availableDomains?.map((domain) => {return {
                                title: domain,
                                value: domain,
                            }}),
                        ]"
                        input-classes="select-sm"
                        class="text-sm text-gray-500"
                        :disabled="isLoading"
                    />
                </div>
                <slot name="header"/>
            </div>
        </div>

        <div class="w-full flex justify-center" v-if="isLoading">
            <loading-spinner dark/>
        </div>
        <div v-else-if="this.data?.items?.length"
             class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-x-8 mb-4 px-4 gap-y-4">
            <div class="flex flex-col" v-for="dataItem in pageItems" :key="dataItem.title">
                <h4 class="font-bold text-sm text-center whitespace-nowrap overflow-hidden overflow-ellipsis w-full mb-1"
                    :title="$t('campaigns.planfact.fields.' + dataItem.title)"
                >{{ $t('campaigns.planfact.fields.' + dataItem.title) }}</h4>
                <p class="font-bold text-sm text-center text-gray-500 mb-1">
                    {{ separateNumber(r(dataItem.summary.plan)) }}{{ dataItem.units ?? '' }}
                </p>
                <div class="h-32 w-full mb-1">
                    <plan-fact-circle-chart :data="dataItem" :units="dataItem.units ?? ''"/>
                </div>
                <div class="h-24 w-full mb-1">
                    <plan-fact-linear-chart :data="dataItem"/>
                </div>
            </div>
        </div>
        <div v-else class="text-center">
            {{ $t('campaigns.planfact.block.notSpecifiedForSelectedMonth') }}
        </div>

        <div class="flex justify-end">
            <plan-fact-paginator
                v-model:cur-page.number="page"
                :max-page="pagesCount"
            />
        </div>
    </div>
</template>

<script>
import PlanFactCircleChart from "@/Pages/Campaign/Components/PlanFactCircleChart.vue";
import {Link} from '@inertiajs/vue3';
import PlanFactPaginator from "@/Pages/Campaign/Components/PlanFactPaginator.vue";
import PlanFactLinearChart from "@/Pages/Campaign/Components/PlanFactLinearChart.vue";
import {isEmpty, ls, n, r, separateNumber} from '@/utils';
import axios from 'axios';
import LoadingSpinner from "@/Components/LoadingSpinner.vue";
import {makeDateRangeMomentObject} from "@/dateRange.js";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";

const ITEMS_PER_PAGE = 8;

export default {
    name: "PlanFact",
    components: {SearchableSelect, LoadingSpinner, PlanFactLinearChart, PlanFactPaginator, PlanFactCircleChart, Link},

    props: {
        campaignId: {
            type: Number,
            required: true,
        },
        period: {
            type: [Object, null],
            required: false,
            default: null,
        },
    },

    data() {
        return {
            ITEMS_PER_PAGE,
            isUnavailable: true,
            isLoading: true,
            page: 1,
            data: {
                plans: [],
                selectedPlan: null,
                selectedDomain: null,

                items: [],
            },
            filters: {
                plan_id: null,
                domain: null,
            },
        };
    },

    computed: {
        campaigns() {
            return this.$page.props.campaigns;
        },

        selectedPeriod() {
            let period = makeDateRangeMomentObject(this.period ?? this.$page.props.period);
            return {
                from: new Date(period.from),
                to: new Date(period.to),
            };
        },

        pageItems() {
            return this.getPageItems();
        },

        pagesCount() {
            return r(n(this.data?.items?.length) / ITEMS_PER_PAGE, 0, 'c');
        },

        routeToAdd() {
            if (!isEmpty(this.currentCampaign?.sources)) {
                return this.route('campaign.planfact.add.show', this.campaignId)
            }
            return this.route('campaign.source', this.campaignId)
        },

        currentCampaign() {
            let selId = this.getSelectedCamp();
            for (let i in this.campaigns) {
                if (this.campaigns[i].id === selId) {
                    return this.campaigns[i];
                }
            }
            return null;
        }
    },

    methods: {
        n, separateNumber, r, isEmpty,

        getSelectedCamp() {
            return n(
                this.$page.props?.activeCampaignId
                ?? this.$page.props?.campaignId
                ?? this.$page.props?.campaign?.id
                ?? this.route()?.params?.campaign
            );
        },

        getPageItems(_page = null) {
            let page = Math.max(_page ?? this.page, 1);

            let from = (page - 1) * ITEMS_PER_PAGE;
            let to = from + ITEMS_PER_PAGE;

            return this.data?.items?.slice(from, to) ?? [];
        },
        updateFilters() {
            ls.set(`planfact:cId=${this.campaignId}:plan_id`, this.data.selectedPlan.id);
            this.filters.plan_id = this.data.selectedPlan.id;
            this.filters.domain = this.data.selectedDomain;
        },
    },

    watch: {
        filters: {
            deep: true,
            async handler() {
                if (this.isLoading) {
                    return;
                }

                this.isLoading = true;

                this.data = (await axios.get(this.route('campaign.planfact.init', {
                    campaign: this.campaignId,
                    period: this.selectedPeriod,
                    ...this.filters,
                }))).data;

                this.updateFilters();

                await this.$nextTick();
                this.isLoading = false;
            },
        },
        period: {
            deep: true,
            async handler() {
                this.isLoading = true;
                axios.get(this.route('campaign.planfact.init', {
                    campaign: this.campaignId,
                    period: this.selectedPeriod,
                    ...this.filters,
                }))
                    .then((res) => {
                        this.isUnavailable = false;
                        this.data = res.data;

                        this.updateFilters();
                    })
                    .catch(() => {
                        this.isUnavailable = true;
                    }).finally(() => {
                    this.isLoading = false;
                });
            },
        }
    },

    async beforeMount() {
        this.filters.plan_id = ls.getN(`planfact:cId=${this.campaignId}:plan_id`);

        axios.get(this.route('campaign.planfact.init', {
            campaign: this.campaignId,
            period: this.selectedPeriod,
            ...this.filters,
        }))
            .then((res) => {
                this.isUnavailable = false;
                this.data = res.data;

                this.updateFilters();
            })
            .catch(() => {
                this.isUnavailable = true;
            }).finally(() => {
            this.isLoading = false;
        });
    },
}
</script>
