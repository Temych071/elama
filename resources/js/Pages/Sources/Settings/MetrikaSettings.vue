<template>
    <Head>
        <title>{{ $t('sources.settings.metrika.title') }}</title>
    </Head>
    <Authenticated>
        <section class="card-md p-8">
            <h1 class="title mb-5">{{ $t('sources.settings.metrika.header') }}</h1>

            <article>
                <h2 class="title-sm mb-4">1. {{ $t('sources.settings.metrika.selectSources') }}</h2>
                <div class="mb-3">
                    <label for="counter-selector" class="form-label">{{
                            $t('sources.settings.metrika.counter')
                        }}</label>

                    <select
                        id="counter-selector"
                        class="form-select"
                        v-model="form.counter"
                        :disabled="isSettingsLoaded"
                    >
                        <option disabled hidden selected value="null">
                            {{ $t('sources.settings.metrika.counterPlaceholder') }}
                        </option>
                        <option v-for="counter in counters"
                                :key="counter.id"
                                :value="counter">{{ `${counter.name} (${counter.id})` }}
                        </option>
                    </select>

                    <div v-if="form.counter" class="mt-2 text-black text-opacity-70 text-sm">
                        <p v-if="form.counter?.ecommerce">
                            Для выбранного счётчика включена электронная коммерция.
                            Она будет учитываться в статистике.
                        </p>
                        <p v-else>
                            Для выбранного счётчика отключена электронная коммерция.
                        </p>
                    </div>
                </div>
            </article>

            <div v-if="form.counter">
                <div class="divider my-4"></div>

                <article class="mb-4">
                    <h2 class="title-sm mb-4">2. {{ $t('sources.settings.metrika.selectGoals') }}</h2>
                    <div v-if="availableGoals && availableGoals.length">
                        <label class="form-label">
                            {{ $t('sources.settings.metrika.goalsNote', {appName: $page.props.app.name}) }}
                        </label>

                        <checkbox-list
                            :select-all-label="$t('sources.settings.metrika.selectAllGoals')"
                            v-model="form.goals"
                            title-key="name"
                            :items="availableGoals"
                        />
                    </div>
                    <div v-else v-html="$t('sources.settings.metrika.goalsAbsent')"></div>
                </article>

                <ValidationErrors/>

                <button type="submit"
                        @click.prevent="submit"
                        class="btn btn-md btn-outline-primary mt-4"
                        v-if="availableGoals && availableGoals.length"
                >{{ $t('sources.settings.metrika.sendButton') }}
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
import ValidationErrors from "@/Components/ValidationErrors.vue";
import CheckboxList from "@/Components/Forms/CheckboxList.vue";

export default {
    props: {
        counters: Array,
        source_type: String,
    },
    components: {
        CheckboxList,
        ValidationErrors,
        Label,
        Checkbox,
        Authenticated,
        Head,
    },
    data() {
        return {
            form: this.$inertia.form(this.$page.props.settings ?? {
                counter: null,
                goals: [],
            }),
            isSelectedAll: false,
        };
    },
    computed: {
        availableGoals() {
            return this.form.counter?.goals ?? [];
        },
        isSettingsLoaded() {
            let settings = this.$page.props.settings;
            return settings !== null && settings.counter !== null;
        },
    },
    methods: {
        submit() {
            this.form.put(this.route(`campaign.source.settings.${this.source_type}.store`, route().params.campaign), {
                onSuccess: () => {
                    this.form.reset();
                },
            })
        },
    },
}
</script>

