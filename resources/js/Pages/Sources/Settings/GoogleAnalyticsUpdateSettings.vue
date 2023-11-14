<template>
    <Head>
        <title>{{ $t('sources.settings.analytics.title') }}</title>
    </Head>
    <Authenticated>
        <section class="card-md p-8">
            <h1 class="title mb-5">{{ $t('sources.settings.analytics.header') }}</h1>

            <article>
                <h2 class="title-sm mb-4">{{ $t('sources.settings.analytics.selectedCounter') }}</h2>
                <div class="mb-3">
                    <label for="counter-selector" class="form-label">{{
                            $t('sources.settings.analytics.counter')
                        }}</label>
                    <select id="counter-selector" class="form-select" disabled>
                        <option selected :value="settings.counter.id">
                            {{ settings.counter.name }}
                        </option>
                    </select>
                </div>
            </article>

            <div>
                <div class="divider my-4"></div>

                <article class="mb-4">
                    <h2 class="title-sm mb-4">{{ $t('sources.settings.analytics.selectGoals') }}</h2>
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
                        <div v-else class="text-gray text-center">
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

        settings: String,
        goals: String,
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
            form: this.$inertia.form({
                goals: this.settings.goals,
            }),

            isSelectedAll: false,
        };
    },
    mounted() {
        this.checkAllSelections();
    },
    watch: {
        'form.goals'() {
            this.checkAllSelections();
        },
    },
    methods: {
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
            this.form.put(this.route(`campaign.source.settings.${this.source_type}.update`, route().params.campaign), {
                onSuccess: () => {
                    this.form.reset();
                },
            })
        },
    },
}
</script>

