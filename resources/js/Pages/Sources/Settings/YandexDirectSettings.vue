<template>
    <Head>
        <title>{{ $t('sources.settings.yandexDirect.title') }}</title>
    </Head>

    <div class="settings-direct">
        <section class="card-md p-8">
            <h1 class="title mb-5">{{ $t('sources.settings.yandexDirect.header') }}</h1>

            <article>
                <h2 class="title-sm mb-4">{{ $t('sources.settings.yandexDirect.selectCampaigns') }}</h2>
                <checkbox-list v-if="directCampaigns && directCampaigns.length"
                               title-key="Name"
                               v-model="form.campaigns"
                               :items="directCampaigns"
                               select-all-label="Выбрать все кампании"
                               :null-if-selected-all="true"
                />
                <div v-else class="text-center text-gray-500">
                    {{ $t('sources.settings.yandexDirect.campaignsNotFound') }}
                </div>
            </article>

            <validation-errors/>

            <button type="submit"
                    @click.prevent="submit"
                    class="btn btn-md btn-outline-primary mt-4"
            >{{ $t('sources.settings.yandexDirect.saveButton') }}
            </button>
        </section>

        <dg-mark-card :mark="dgMark" />
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated.vue";
import {Head} from '@inertiajs/vue3';
import Checkbox from "@/Components/Checkbox.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import CheckboxList from "@/Components/Forms/CheckboxList.vue";
import DgMarkCard from "@/Pages/Sources/Settings/Components/DgMarkCard.vue";

export default {
    layout: Layout,
    components: {DgMarkCard, CheckboxList, ValidationErrors, Checkbox, Head},
    props: {
        directCampaigns: Array,
        source_type: String,
        dgMark: String,
    },
    data() {
        return {
            form: this.$inertia.form({
                campaigns: [],
                selectAllCampaigns: false,
                ...this.$page.props.settings,
            }),
        };
    },
    computed: {
        isSettingsLoaded() {
            let settings = this.$page.props.settings;
            return settings !== null && settings.campaigns?.length;
        },
    },
    beforeMount() {
        if (this.form.selectAllCampaigns) {
            this.form.campaigns = this.directCampaigns;
        }
    },
    methods: {
        submit() {
            let cIds = this.directCampaigns.map((item) => item.Id);
            this.form.campaigns = this.form.campaigns?.filter((item) => cIds.includes(item.Id)) ?? null;

            this.form.put(this.route(`campaign.source.settings.${this.source_type}.store`, route().params.campaign), {
                onSuccess: () => {
                    this.form.reset();
                },
            })
        },
    },
}
</script>
