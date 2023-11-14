<template>
    <Head>
        <title>{{ $t('sources.settings.adsense.title') }}</title>
    </Head>
    <Authenticated>
        <div class="settings-direct">
            <section class="card-md p-8">
                <h1 class="title mb-5">{{ $t('sources.settings.adsense.header') }}</h1>

                <article>
                    <h2 class="title-sm mb-4">1. {{ $t('sources.settings.adsense.selectAccount') }}</h2>
                    <div class="mb-3">
                        <label for="account-selector" class="form-label">
                            {{ $t('sources.settings.adsense.account') }}
                        </label>
                        <select id="account-selector" class="form-select" v-model="form.account">
                            <option disabled hidden selected value="null">
                                {{ $t('sources.settings.adsense.accountPlaceholder') }}
                            </option>
                            <option v-for="account in available_accounts"
                                    :key="account"
                                    :value="account">{{ `ID: ${account}` }}
                            </option>
                        </select>
                    </div>
                </article>

                <div v-if="form.account">
                    <div class="divider my-4"></div>

                    <article class="mb-4">
                        <h2 class="title-sm mb-4">2. {{ $t('sources.settings.adsense.selectCampaigns') }}</h2>
                        <div>
                            <label class="form-label">
                                {{ $t('sources.settings.adsense.campaignsNote', {appName: $page.props.app.name}) }}
                            </label>

                            <CheckboxList
                                v-if="availableCampaigns && availableCampaigns.length"
                                :items="availableCampaigns"
                                title-key="name"
                                :select-all-label="$t('sources.settings.adsense.selectAllCampaigns')"
                                v-model="form.campaigns"
                            />
                            <div v-else-if="isCampaignsLoading" class="text-gray">
                                <LoadingSpinner dark class="h-8 mr-2"/>
                                {{ $t('sources.settings.adsense.campaignsLoading') }}
                            </div>
                            <div v-else>
                                {{ $t('sources.settings.adsense.campaignsNotFound') }}
                            </div>
                        </div>
                    </article>

                    <validation-errors/>

                    <button type="submit"
                            @click.prevent="submit"
                            class="btn btn-md btn-outline-primary"
                    >{{ $t('sources.settings.adsense.sendButton') }}
                    </button>
                </div>
            </section>
        </div>
    </Authenticated>
</template>

<script setup>
    import Authenticated from "@/Layouts/Authenticated.vue";
    import {Head, useForm, usePage} from '@inertiajs/vue3';
    import Label from "@/Components/Label.vue";
    import LoadingSpinner from "@/Components/LoadingSpinner.vue";
    import ValidationErrors from "@/Components/ValidationErrors.vue";
    import CheckboxList from "@/Components/Forms/CheckboxList.vue";
    import {nextTick, ref, watch} from "vue";

    const props = defineProps({
        available_accounts: Array,
    });

    const form = useForm(usePage().props.value.settings ?? {
        account: null,
        campaigns: [],
    });

    const isCampaignsLoading = ref(false);
    const availableCampaigns = ref([]);

    watch(() => form.account, (val, old) => loadCampaigns(val));

    const loadCampaigns = async (account) => {
        if (!account) {
            return;
        }

        isCampaignsLoading.value = true;

        await nextTick();

        const url = route('campaign.source.settings.google-ads.campaigns', route().params.campaign);
        const res = await axios.get(url, {
            params: {
                account: account,
            },
        })

        availableCampaigns.value = res.data;
        isCampaignsLoading.value = false;
    }

    const submit = async () => {
        const url = route(`campaign.source.settings.google-ads.store`, route().params.campaign);
        form.post(url, {
            onSuccess: () => {
                this.form.reset();
            },
        });
    }
</script>
