<template>
    <Head>
        <title>{{ $t('sources.settings.analytics.title') }}</title>
    </Head>
    <Authenticated>
        <section class="card-md p-8">
            <h1 class="title mb-5">{{ $t('sources.settings.analytics.header') }}</h1>

            <article>
                <h2 class="title-sm mb-4">1. {{ $t('sources.settings.analytics.selectAccount') }}</h2>
                <div class="mb-3">
                    <label for="account-selector" class="form-label">{{
                            $t('sources.settings.analytics.account')
                        }}</label>
                    <select id="account-selector" class="form-select" v-model="form.account">
                        <option disabled hidden selected value="null">
                            {{ $t('sources.settings.analytics.accountPlaceholder') }}
                        </option>
                        <option v-for="account in summaries"
                                :key="account.id"
                                :value="account">{{ `${account.name} (ID: ${account.id})` }}
                        </option>
                    </select>
                </div>
            </article>

            <article v-if="form.account">
                <h2 class="title-sm mb-4">2. {{ $t('sources.settings.analytics.selectApp') }}</h2>
                <div class="mb-3">
                    <label for="app-selector" class="form-label">{{ $t('sources.settings.analytics.app') }}</label>
                    <select id="app-selector" class="form-select" v-model="form.app">
                        <option disabled hidden selected value="null">{{
                                $t('sources.settings.analytics.appPlaceholder')
                            }}
                        </option>
                        <option v-for="app in availableApps"
                                :key="app.id"
                                :value="app">{{ `${app.name} (ID: ${app.id})` }}
                        </option>
                    </select>
                </div>
            </article>

            <article v-if="form.app">
                <h2 class="title-sm mb-4">3. {{ $t('sources.settings.analytics.selectCounter') }}</h2>
                <div class="mb-3">
                    <label for="counter-selector" class="form-label">{{
                            $t('sources.settings.analytics.counter')
                        }}</label>
                    <select id="counter-selector" class="form-select" v-model="form.counter">
                        <option disabled hidden selected value="null">
                            {{ $t('sources.settings.analytics.counterPlaceholder') }}
                        </option>
                        <option v-for="counter in availableCounters"
                                :key="counter.id"
                                :value="counter">{{ `${counter.name} (ID: ${counter.id})` }}
                        </option>
                    </select>
                </div>
            </article>

            <div v-if="form.counter">
                <div class="divider my-4"></div>

                <article class="mb-4">
                    <h2 class="title-sm mb-4">4. {{ $t('sources.settings.analytics.selectGoals') }}</h2>
                    <div>
                        <label class="form-label">
                            {{ $t('sources.settings.analytics.goalsNote', {appName: $page.props.app.name}) }}
                        </label>

                        <template v-if="goals && goals.length">
                            <div class="mb-8">
                                <Checkbox @update:checked="onSelectAll"
                                          v-model:checked="isSelectedAll"
                                          class="pb-1.5"
                                          ref="cbSelectAll"
                                >{{ $t('sources.settings.analytics.selectAllGoals') }}
                                </Checkbox>
                            </div>

                            <div v-for="goal in goals" :key="goal.id">
                                <Checkbox v-model:checked="form.goals"
                                          :value="goal"
                                          class="pb-1.5"
                                >{{ goal.name }}
                                </Checkbox>
                            </div>
                        </template>
                        <div v-else-if="isLoadingGoals" class="text-gray text-center">
                            <loading-spinner dark class="h-8 mr-2"/>
                            {{ $t('sources.settings.analytics.goalsLoading') }}
                        </div>
                        <div v-else>
                            {{ $t('sources.settings.analytics.goalsNotFound') }}
                        </div>
                    </div>
                </article>

                <validation-errors/>

                <button type="submit"
                        @click.prevent="submit"
                        class="btn btn-md btn-outline-primary"
                >{{ $t('sources.settings.analytics.sendButton') }}
                </button>
            </div>
        </section>
    </Authenticated>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated.vue";
import {Head} from '@inertiajs/vue3';
import Checkbox from "@/Components/Checkbox.vue";
import Label from "@/Components/Label.vue";
import LoadingSpinner from "@/Components/LoadingSpinner.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

export default {
    props: {
        campaign_id: Number,
        source_type: String,
        summaries: Object,
    },
    components: {
        ValidationErrors,
        LoadingSpinner,
        Label,
        Checkbox,
        Authenticated,
        Head,
    },
    data() {
        return {
            form: this.$inertia.form(this.$page.props.settings ?? {
                account: null,
                app: null,
                counter: null,
                goals: [],
            }),

            goals: null,
            isSelectedAll: false,

            isLoadingGoals: false,
        };
    },
    mounted() {
        this.checkAllSelections();
    },
    watch: {
        'form.account'() {
            this.form.app = null;
        },
        'form.app'() {
            this.form.counter = null;
        },
        async 'form.counter'() {
            this.form.goals = [];
            this.goals = [];
            await this.loadGoals();

            this.checkAllSelections();
        },
        'form.goals'() {
            this.checkAllSelections();
        },
    },
    computed: {
        availableApps() {
            return this.form.account?.webProperties ?? [];
        },
        availableCounters() {
            return this.form.app?.profiles ?? [];
        },
    },
    methods: {
        async loadGoals() {
            if (
                !this.form.account
                || !this.form.app
                || !this.form.counter
            ) {
                return;
            }

            this.isLoadingGoals = true;
            await this.$nextTick();

            this.goals = (await axios.get(
                this.route('campaign.source.settings.google-analytics.goals', route().params.campaign),
                {
                    params: {
                        account_id: this.form.account.id,
                        property_id: this.form.app.id,
                        counter_id: this.form.counter.id,
                    }
                }
            )).data;
            this.isLoadingGoals = false;
        },

        checkAllSelections() {
            this.isSelectedAll = (
                this.goals
                && this.form.goals.length === this.goals.length
            );
        },

        onSelectAll(to) {
            this.form.goals = to ? this.goals : [];
        },

        submit() {
            this.form.post(this.route(`campaign.source.settings.${this.source_type}.store`, route().params.campaign), {
                onSuccess: () => {
                    this.form.reset();
                },
            })
        },
    },
}
</script>

