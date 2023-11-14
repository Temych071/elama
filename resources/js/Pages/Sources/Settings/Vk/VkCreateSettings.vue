<template>
    <Head>
        <title>{{ $t('sources.settings.vk.title') }}</title>
    </Head>
    <Authenticated>
        <section class="card-md p-8">
            <h1 class="title mb-5">{{ $t('sources.settings.vk.header') }}</h1>

            <article>
                <h2 class="title-sm mb-4">1. {{ $t('sources.settings.vk.selectAccount') }}</h2>
                <div class="mb-3">
                    <label for="account-selector" class="form-label">{{
                            $t('sources.settings.vk.account')
                        }}</label>
                    <select id="account-selector" class="form-select" v-model="form.account">
                        <option disabled hidden selected value="null">
                            {{ $t('sources.settings.vk.accountPlaceholder') }}
                        </option>
                        <option v-for="account in accounts"
                                :key="account.account_id"
                                :value="account">{{ `${account.account_name} (ID: ${account.account_id})` }}
                        </option>
                    </select>
                </div>
            </article>

            <div v-if="form.account && isAgencyAccount(form.account)">
                <div class="divider my-4"></div>

                <article class="mb-3">
                    <h2 class="title-sm mb-4">2. {{ $t('sources.settings.vk.selectClient') }}</h2>
                    <div>
                        <select
                            id="client-selector"
                            class="form-select"
                            v-model="form.client"
                            v-if="availableClients && availableClients.length">
                            <option disabled hidden selected :value="null">
                                {{ $t('sources.settings.vk.selectClient') }}
                            </option>
                            <option v-for="client in availableClients"
                                    :key="client.id"
                                    :value="client">{{ `${client.name} (ID: ${client.id})` }}
                            </option>
                        </select>
                        <div v-else-if="isClientsLoading" class="text-gray">
                            <LoadingSpinner dark class="h-8 mr-2"/>
                            {{ $t('sources.settings.vk.clientsLoading') }}
                        </div>
                        <div v-else>
                            {{ $t('sources.settings.vk.clientsNotFound') }}
                        </div>
                    </div>
                </article>
            </div>

            <div v-if="isCampaignsShow">
                <div class="divider my-4"></div>

                <article class="mb-4">
                    <h2 class="title-sm mb-4">3. {{ $t('sources.settings.vk.selectCampaigns') }}</h2>
                    <div>
                        <label class="form-label">
                            {{ $t('sources.settings.vk.campaignsNote', {appName: $page.props.app.name}) }}
                        </label>

                        <CheckboxList
                            v-if="availableCampaigns && availableCampaigns.length"
                            :items="availableCampaigns"
                            :title-key="campaignTitle"
                            :select-all-label="$t('sources.settings.adsense.selectAllCampaigns')"
                            v-model="form.campaigns"
                            :null-if-selected-all="true"
                        />
                        <div v-else-if="isCampaignsLoading" class="text-gray">
                            <LoadingSpinner dark class="h-8 mr-2"/>
                            {{ $t('sources.settings.vk.campaignsLoading') }}
                        </div>
                        <div v-else>
                            {{ $t('sources.settings.vk.campaignsNotFound') }}
                        </div>
                    </div>
                </article>

                <div class="divider my-4"></div>

                <div class="mb-4">
                    <h2 class="title-sm">3. Сообщения и лид-формы ВК</h2>

                    <label class="form-label mt-3 mb-6">Входящие сообщения и лид-формы будут учитываться как заявки</label>

                    <Checkbox v-model:checked="isLeadFormEnabled" class="mb-4">Подключить сообщения группы</Checkbox>

                    <LeadFormSettings
                        class="pl-4"
                        v-if="isLeadFormEnabled"
                        :campaigns="campaigns"
                        :source-key="sourceKey"
                        v-model="form.webhooks"
                        v-model:leads-forms="form.is_vk_lead_forms"
                        v-model:leads-messages="form.is_vk_lead_messages"
                    />
                </div>

                <validation-errors/>

                <button v-if="availableCampaigns && availableCampaigns.length"
                        type="submit"
                        @click.prevent="submit"
                        class="btn btn-md btn-outline-primary"
                >{{ $t('sources.settings.vk.sendButton') }}
                </button>
            </div>

        </section>

        <dg-mark-card :mark="dgMark" />
    </Authenticated>
</template>

<script setup>
import Authenticated from "@/Layouts/Authenticated.vue";
import {Head, useForm} from '@inertiajs/vue3';
import Label from "@/Components/Label.vue";
import LoadingSpinner from "@/Components/LoadingSpinner.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import {computed, nextTick, ref, watch} from "vue";
import CheckboxList from "@/Components/Forms/CheckboxList.vue";
import LeadFormSettings from "@/Pages/Sources/Settings/Vk/LeadFormSettings.vue";
import Checkbox from "@/Components/Checkbox.vue";
import DgMarkCard from "@/Pages/Sources/Settings/Components/DgMarkCard.vue";

const props = defineProps({
    campaigns: Array,
    source_type: String,
    accounts: Object,
    sourceKey: String,
    webhooks: Array,
    dgMark: String,
});

const form = useForm({
    account: null,
    client: null,
    key: props.sourceKey,
    webhooks: props.webhooks,
    campaigns: [],
    is_vk_lead_forms: true,
    is_vk_lead_messages: true,
});

const isCampaignsLoading = ref(false);
const isClientsLoading = ref(false);
const availableClients = ref([]);
const availableCampaigns = ref([]);
const isLeadFormEnabled = ref(false);

const campaignTitle = (campaign) => `${campaign.name} (${campaign.id})`;

const isAgencyAccount = (account) => account['account_type'] === 'agency';

const isCampaignsShow = computed(() => {
    if (form.account && isAgencyAccount(form.account)) {
        return !!form.client;
    }

    return !!form.account;
})

watch(() => form.account, (val) => {
    form.client = null;
    form.campaigns = [];

    if (isAgencyAccount(val)) {
        loadClients(val);
    } else {
        loadCampaigns(val);
    }
});

watch(() => form.client, (val) => loadCampaigns(form.account, val))

const loadClients = async (account) => {
    if (!account) {
        return;
    }

    availableClients.value = [];
    availableCampaigns.value = [];
    isClientsLoading.value = true;

    await nextTick();

    const url = route('campaign.source.settings.vk.clients', route().params.campaign);
    const res = await axios.get(url, {
        params: {
            account: +account.account_id,
        },
    })

    availableClients.value = res.data;
    isClientsLoading.value = false;
}

const loadCampaigns = async (account, client) => {
    if (!account) {
        return;
    }

    availableCampaigns.value = [];
    isCampaignsLoading.value = true;

    await nextTick();

    const url = route('campaign.source.settings.vk.campaigns', route().params.campaign);

    const params = {
        account: +account.account_id,
    };

    if (client) {
        params.client = +client.id;
    }

    const res = await axios.get(url, {
        params: params,
    })

    availableCampaigns.value = res.data;
    isCampaignsLoading.value = false;
}


const submit = async () => {
    const url = route(`campaign.source.settings.vk.store`, route().params.campaign);
    form
        .transform((data) => ({
            ...data,
            webhooks: isLeadFormEnabled.value ? data.webhooks : null,
        }))
        .post(url, {
            onSuccess: () => {
                this.form.reset();
            },
        });
}
</script>

