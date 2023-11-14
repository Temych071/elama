<template>
    <Head>
        <title>{{ $t('sources.settings.vk.title') }}</title>
    </Head>
    <Authenticated>
        <Breadcrumb>
            <BreadcrumbItem :href="route('campaign.settings')">Настройки</BreadcrumbItem>
            <BreadcrumbItem :href="route('campaign.browse', route().params.campaign)">
                {{ campaigns.find(it => +it.id === +route().params.campaign).name }}
            </BreadcrumbItem>
            <BreadcrumbItem :href="route('campaign.source', route().params.campaign)">Источники</BreadcrumbItem>
            <BreadcrumbItem>ВКонтакте</BreadcrumbItem>
        </Breadcrumb>

        <section class="card-md p-8">
            <h1 class="title mb-5">{{ $t('sources.settings.vk.header') }}</h1>

            <article>
                <h2 class="title-sm mb-4">1. {{ $t('sources.settings.vk.selectAccount') }}</h2>
                <div class="mb-3">
                    <label for="account-selector" class="form-label">{{
                            $t('sources.settings.vk.account')
                        }}</label>
                    <input type="text" disabled :value="settings.account" class="form-control">
                </div>
            </article>

            <article v-if="settings.client">
                <div class="divider my-4"></div>
                <div class="mb-3">
                    <h2 class="title-sm mb-4">2. {{ $t('sources.settings.vk.selectClient') }}</h2>
                    <div>
                        <input type="text" disabled :value="settings.client" class="form-control">
                    </div>
                </div>
            </article>

            <article>
                <div class="divider my-4"></div>

                <div class="mb-4">
                    <h2 class="title-sm mb-4">2. {{ $t('sources.settings.vk.selectCampaigns') }}</h2>
                    <div>
                        <label class="form-label">
                            {{ $t('sources.settings.vk.campaignsNote', {appName: $page.props.app.name}) }}
                        </label>

                        <CheckboxList
                            v-if="available_campaigns && available_campaigns.length"
                            :items="available_campaigns"
                            :title-key="campaignTitle"
                            :select-all-label="$t('sources.settings.adsense.selectAllCampaigns')"
                            v-model="form.campaigns"
                            :null-if-selected-all="true"
                        />
                        <div v-else>
                            {{ $t('sources.settings.vk.campaignsNotFound') }}
                        </div>
                    </div>
                </div>

                <div class="divider my-4"></div>

                <div class="mb-4">
                    <h2 class="title-sm">3. Сообщения и лид-формы ВК</h2>

                    <label class="form-label mt-3 mb-6">Входящие сообщения и лид-формы будут учитываться как заявки</label>

                    <Checkbox v-model:checked="isLeadFormEnabled" class="mb-4">Подключить сообщения группы</Checkbox>

                    <LeadFormSettings
                        class="pl-4"
                        v-if="isLeadFormEnabled"
                        :campaigns="campaigns"
                        :source-key="settings.key"
                        v-model="form.webhooks"
                        v-model:leads-forms="form.is_vk_lead_forms"
                        v-model:leads-messages="form.is_vk_lead_messages"
                    />
                </div>

                <validation-errors/>

                <button
                    type="submit"
                    @click.prevent="submit"
                    class="btn btn-md btn-outline-primary"
                >{{ $t('sources.settings.vk.sendButton') }}
                </button>
            </article>
        </section>

        <dg-mark-card :mark="dgMark" />
    </Authenticated>
</template>

<script setup>
import Authenticated from "@/Layouts/Authenticated.vue";
import {Head, useForm} from '@inertiajs/vue3';
import Label from "@/Components/Label.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import {ref} from "vue";
import CheckboxList from "@/Components/Forms/CheckboxList.vue";
import Breadcrumb from "@/Components/Breadcrumbs/Breadcrumb.vue";
import BreadcrumbItem from "@/Components/Breadcrumbs/BreadcrumbItem.vue";
import LeadFormSettings from "@/Pages/Sources/Settings/Vk/LeadFormSettings.vue";
import Checkbox from "@/Components/Checkbox.vue";
import DgMarkCard from "@/Pages/Sources/Settings/Components/DgMarkCard.vue";

const props = defineProps({
    campaigns: Array,
    source_type: String,
    accounts: Object,
    settings: Object,
    available_campaigns: Array,
    dgMark: String,
});

const isLeadFormEnabled = ref(!_.isEmpty(props.settings.webhooks));

const form = useForm({
    campaigns: props.settings.selected_campaigns,
    webhooks: props.settings.webhooks,
    is_vk_lead_forms: !!props.settings.is_vk_lead_forms,
    is_vk_lead_messages: !!props.settings.is_vk_lead_messages,
});

const campaignTitle = (campaign) => `${campaign.name} (${campaign.id})`;

const submit = async () => {
    const url = route(`campaign.source.settings.vk.update`, route().params.campaign);

    form
        .transform((data) => ({
            ...data,
            webhooks: isLeadFormEnabled.value ? data.webhooks : null,
        }))
        .put(url, {
            onSuccess: () => this.form.reset(),
        });
}
</script>

