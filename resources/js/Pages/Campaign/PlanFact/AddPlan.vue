<template>
    <Head>
        <title>{{ $t('campaigns.planfact.settings.add.title.' + (plan === null ? 'new' : 'edit')) }}</title>
    </Head>

    <div class="plan-fact-add card-md px-9 py-5">
        <h2 class="uppercase mb-5 font-bold text-black text-lg">
            {{ $t('campaigns.planfact.settings.add.header.' + (plan === null ? 'new' : 'edit')) }}
        </h2>

        <form @submit.prevent="onSubmit">
            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.settings.add.name') }}</label>
                <input
                    v-model.trim="form.name"
                    type="text"
                    :placeholder="$t('campaigns.planfact.settings.add.namePlaceholder')"
                    class="form-control"
                />
            </div>

            <div class="form-field mb-4" v-if="sources.length || hasSeo">
                <label class="form-label">{{ $t('campaigns.planfact.settings.add.sources') }}</label>
                <sources-checkbox-list
                    :items="sources"
                    v-model="form.sources"
                    v-model:seo="form.seo.enabled"
                    :has-seo="hasSeo"

                    :title-key="(item) => $t('sources.names.' + item)"
                    :select-all-label="$t('campaigns.planfact.settings.add.selAllSources')"
                />
            </div>

            <div class="rounded-md border border-gray-200 p-4 mb-4">
                <checkbox v-model:checked="showAdvSettings">
                    {{ $t('campaigns.planfact.settings.add.showExtendedSettings') }}
                </checkbox>
                <div class="mt-4" v-show="showAdvSettings">
                    <div class="form-field mb-4">
                        <label class="form-label">{{ $t('campaigns.planfact.settings.add.campaignName') }}</label>
                        <tags-input-autocomplete
                            v-model="form.campaign_name"
                            :options="campaign_names"
                            :placeholder="$t('campaigns.planfact.settings.add.campaignNamePlaceholder')"
                        />
                    </div>

                    <div class="form-field mb-4">
                        <label class="form-label">{{ $t('campaigns.planfact.settings.add.utmCampaign') }}</label>
                        <tags-input-autocomplete
                            v-model="form.utm_campaign"
                            :options="utm_campaigns"
                            :placeholder="$t('campaigns.planfact.settings.add.utmCampaignPlaceholder')"
                        />
                    </div>

                    <div class="form-field mb-4">
                        <label class="form-label">UTM Medium (utm_medium)</label>
                        <tags-input-autocomplete
                            v-model="form.utm_medium"
                            :options="utm_mediums"
                        />
                    </div>

                    <div class="form-field mb-4">
                        <label class="form-label">UTM Источник (utm_source)</label>
                        <tags-input-autocomplete
                            v-model="form.utm_source"
                            :options="utm_sources"
                        />
                    </div>

                    <div class="form-field mb-4">
                        <label class="form-label">{{ $t('campaigns.planfact.settings.add.device') }}</label>
                        <select class="form-select" v-model="form.device">
                            <option :value="null">{{ $t('campaigns.planfact.settings.add.allDevices') }}</option>
                            <option v-for="device in devices" :value="device">{{
                                    $t('common.devices.' + device)
                                }}
                            </option>
                        </select>
                    </div>

                    <div class="form-field mb-4">
                        <label class="form-label">{{ $t('campaigns.planfact.settings.add.domain') }}</label>
                        <input-autocomplete
                            class="form-control"
                            type="text"
                            v-model.trim="form.domain"
                            :options="domains"
                            :placeholder="$t('campaigns.planfact.settings.add.domainPlaceholder')"
                        />
                    </div>

                    <div class="form-field mb-4" v-if="goals.length">
                        <label class="form-label">{{ $t('campaigns.planfact.settings.add.goals') }}</label>
                        <checkbox-list
                            v-if="goals.length"
                            v-model="form.goals"
                            :items="goals"
                            select-all-label="Выбрать всё"
                            title-key="name"
                        />
                        <span v-else>{{ $t('campaigns.planfact.settings.add.goalsNotFound') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-field mb-4">
                <label class="form-label">{{ $t('campaigns.planfact.settings.add.months') }}</label>

                <div class="mb-2">
                    <button
                        class="btn btn-md btn-outline-info"
                        @click="addPrevMonth"
                        type="button"
                    >{{ $t('campaigns.planfact.settings.add.addPrevMonth') }}
                    </button>
                </div>

                <values-accordion v-model="form.values" :buttons="accordionButtons"/>

                <div class="mt-2">
                    <button
                        class="btn btn-md btn-outline-info"
                        @click="addNextMonth"
                        type="button"
                    >{{ $t('campaigns.planfact.settings.add.addNextMonth') }}
                    </button>
                </div>
            </div>

            <validation-errors/>

            <div class="mt-7 fex flex-row">
                <button class="btn btn-md btn-primary mr-3" type="submit">
                    {{
                        this.plan === null ? $t('campaigns.planfact.settings.add.btn.add') : $t('campaigns.planfact.settings.add.btn.edit')
                    }}
                </button>
                <Link :href="route('campaign.project_settings', {campaign: campaign.id})">
                    <button class="btn btn-md btn-outline-primary" type="button">
                        {{ $t('campaigns.planfact.settings.add.btn.cancel') }}
                    </button>
                </Link>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from '@/Layouts/Authenticated.vue';
import FormSelect from "@/Components/Forms/FormSelect.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import {Link, Head} from '@inertiajs/vue3';
import ValidationErrors from "@/Components/ValidationErrors.vue";
import CheckboxList from "@/Components/Forms/CheckboxList.vue";
import ValuesAccordion from "@/Pages/Campaign/PlanFact/Components/ValuesAccordion.vue";
import InputAutocomplete from "@/Components/Forms/InputAutocomplete.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TagsList from "@/Components/Forms/TagsList.vue";
import TagsInputAutocomplete from "@/Components/Forms/TagsInputAutocomplete.vue";
import SourcesCheckboxList from "@/Pages/Campaign/PlanFact/Components/SourcesCheckboxList.vue";

export default {
    components: {
        SourcesCheckboxList,
        TagsInputAutocomplete,
        TagsList,
        Checkbox,
        InputAutocomplete, Head, ValuesAccordion, CheckboxList, ValidationErrors, TextInput, FormSelect, Link
    },
    layout: Layout,

    props: {
        campaign: {
            type: Object,
            required: true,
        },
        months: {
            type: Array,
            required: true,
        },
        sources: {
            type: Array,
            required: true,
        },
        plan: {
            type: [Object, null],
            required: false,
            default: null,
        },
        connected_sources: {
            type: Array,
            required: false,
            default: [],
        },

        domains: {
            type: Array,
            required: false,
            default: [],
        },
        campaign_names: {
            type: Array,
            required: false,
            default: [],
        },
        utm_campaigns: {
            type: Array,
            required: false,
            default: [],
        },
        utm_sources: {
            type: Array,
            required: false,
            default: [],
        },
        utm_mediums: {
            type: Array,
            required: false,
            default: [],
        },
        devices: {
            type: Array,
            required: false,
            default: ['desktop', 'mobile', 'tablet', 'tv'],
        },
        goals: {
            type: Array,
            required: false,
            default: [],
        },
    },

    data() {
        return {
            form: this.$inertia.form({
                name: '',
                campaign_name: [],
                utm_campaign: [],
                utm_source: [],
                utm_medium: [],
                device: null,
                domain: '',
                goals: [],

                values: [],

                ...this.plan,

                seo: {
                    ...this.plan?.seo ?? {},
                    enabled: this.plan?.seo?.enabled ?? false,
                },

                sources: (() => this.plan?.sources?.filter(source => this.sources.includes(source)) ?? [])(),
            }),
            showAdvSettings: false,
            accordionButtons: {},
            tagsTest: ['test1', 'test2', 'test3', 'test4'],
        };
    },

    computed: {
        hasSeo() {
            for (let i in this.connected_sources) {
                if (['yandex-metrika', 'google-analytics'].includes(this.connected_sources[i])) {
                    return true;
                }
            }
            return false;
        },
    },

    beforeMount() {
        if (!this.form.values.length) {
            this.addNextMonth();
        } else {
            this.form.values[this.form.values.length - 1].__autoopen = true;
        }
        this.updateAccordionButtons();

        // console.log(this.connected_sources);
    },

    methods: {
        onSubmit() {
            if (this.plan !== null) {
                this.form.put(this.route('campaign.planfact.edit.store', {
                    campaign: this.campaign.id,
                    planSettings: this.plan.id,
                }));
            } else {
                this.form.post(this.route('campaign.planfact.add.store', this.campaign));
            }
        },

        async updateAccordionButtons() {
            this.accordionButtons = {};

            for (let i = 0; i < this.form.values.length; i++) {
                this.accordionButtons[String(i)] = {
                    copy: this.copyLastMonth,
                }
            }

            this.accordionButtons['0'].del = this.rmFirstMonth;
            this.accordionButtons[String(this.form.values.length - 1)].del = this.rmLastMonth;

        },

        rmLastMonth() {
            if (confirm('Вы действительно хотите удалить план на последний месяц?')) {
                this.form.values.pop();
            }
        },

        rmFirstMonth() {
            if (confirm('Вы действительно хотите удалить план на первый месяц?')) {
                this.form.values.shift();
            }
        },

        copyLastMonth(data = null) {
            let month = this.getLastMonth();
            month.setMonth(month.getMonth() + 1);

            let lastMonth = data ?? this.form.values[this.form.values.length - 1];

            this.form.values.push({...lastMonth, month, __autoopen: true});
            this.updateAccordionButtons();
        },

        addNextMonth() {
            let month = this.getLastMonth();
            month.setMonth(month.getMonth() + 1);

            this.form.values.push({month, __autoopen: true});
            this.updateAccordionButtons();
        },

        addPrevMonth() {
            let month = this.getFirstMonth();
            month.setMonth(month.getMonth() - 1);

            this.form.values.unshift({month, __autoopen: true});
            this.updateAccordionButtons();
        },

        getLastMonth() {
            let month = null;
            for (let i in this.form.values) {
                let itemMonth = new Date(this.form.values[i].month);
                if (month === null || month.getTime() < itemMonth.getTime()) {
                    month = itemMonth;
                }
            }

            if (month === null) {
                month = new Date();
                month.setDate(3);
                month.setMonth(month.getMonth() - 1);
            }

            return month;
        },

        getFirstMonth() {
            let month = null;
            for (let i in this.form.values) {
                let itemMonth = new Date(this.form.values[i].month);
                if (month === null || month.getTime() > itemMonth.getTime()) {
                    month = itemMonth;
                }
            }

            if (month === null) {
                month = new Date();
                month.setDate(3);
                month.setMonth(month.getMonth() + 1);
            }

            return month;
        },
    },
}
</script>
