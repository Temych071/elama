<template>
    <div>
        <h2 class="planfact-title my-6">План факт</h2>

        <div class="pb-8 grid justify-items-stretch">
            <div class="overflow-x-auto">
                <table class="plan-table">
                    <thead>
                    <tr class="text-slate-800">
                        <th style="padding: 0;"></th>
                        <th>{{ $t('campaigns.planfact.settings.table.header.name') }}</th>
                        <td>{{ $t('campaigns.planfact.settings.table.header.campaign_name') }}</td>
                        <td>{{ $t('campaigns.planfact.settings.table.header.utm_campaign') }}</td>
                        <td>{{ $t('campaigns.planfact.settings.table.header.sources') }}</td>
                        <td>{{ $t('campaigns.planfact.settings.table.header.device') }}</td>
                        <td>{{ $t('campaigns.planfact.settings.table.header.domain') }}</td>
                        <td>{{ $t('campaigns.planfact.settings.table.header.actions') }}</td>
                    </tr>
                    </thead>

                    <draggable tag="tbody" v-if="plansProxy.length" v-model="plansProxy">
                        <template #item="{element: plan}">
                            <tr>
                                <td style="padding: 4px;"><img src="/icons/menu-sm.svg" alt="" class="w-4"></td>

                                <td>{{ plan.name }}</td>
                                <td>
                                    <span v-if="!plan?.campaign_name?.length">
                                        {{ $t('campaigns.planfact.settings.nullFilter') }}
                                    </span>
                                            <span v-else-if="plan.campaign_name.length === 1">
                                        {{ plan.campaign_name[0] }}
                                    </span>
                                            <span v-else>
                                        <tippy class="underline" :options="{allowHTML: true}"
                                               :content="arrToUl(plan.campaign_name)">
                                            {{ plan.campaign_name[0] }}, ...
                                        </tippy>
                                    </span>
                                </td>

                                <td>
                            <span v-if="!plan?.utm_campaign?.length">
                                {{ $t('campaigns.planfact.settings.nullFilter') }}
                            </span>
                                    <span v-else-if="plan.utm_campaign.length === 1">
                                {{ plan.utm_campaign[0] }}
                            </span>
                                    <span v-else>
                                <tippy class="underline" :options="{allowHTML: true}"
                                       :content="arrToUl(plan.utm_campaign)">
                                    {{ plan.utm_campaign[0] }}, ...
                                </tippy>
                            </span>
                                </td>

                                <td>
                                    <template v-if="plan.sources?.length">
                                        <source-icon
                                            v-for="sourceType in plan.sources"
                                            :source-type="sourceType"
                                            class="w-5 ml-2"
                                        />
                                    </template>
                                    <span v-else>-</span>
                                </td>
                                <td>{{
                                        plan.device ? $t('common.devices.' + plan.device) : $t('campaigns.planfact.settings.nullFilter')
                                    }}
                                </td>
                                <td>{{ plan.domain ?? $t('campaigns.planfact.settings.nullFilter') }}</td>
                                <td>
                                    <Link
                                        :href="route('campaign.planfact.edit.show', {planSettings: plan.id, campaign: campaign.id})"
                                        class="text-primary-light underline"
                                    >
                                        <img src="/icons/edit.svg" class="p-2 inline cursor-pointer" alt="edit"/>
                                    </Link>
                                    <img src="/icons/delete.svg"
                                         class="p-2 inline cursor-pointer"
                                         @click="removePlan(plan)"
                                         alt="remove"
                                    />
                                </td>
                            </tr>
                        </template>
                    </draggable>
                    <tr v-else>
                        <td colspan="8" style="text-align: center;">{{ $t('campaigns.planfact.settings.notFound') }}</td>
                    </tr>
                </table>
            </div>

            <Link class="mt-3 justify-self-end" :href="route('campaign.planfact.add.show', {campaign: campaign.id})">
                <button class="btn btn-md btn-primary mx-3">{{ $t('campaigns.planfact.settings.btnAdd') }}</button>
            </Link>
        </div>

<!--        <values-order class="mt-8"-->
<!--                      :href="route('campaign.planfact.order.store', campaign.id)"-->
<!--                      :values="campaign.planfact_order"-->
<!--        />-->

    </div>
</template>

<script setup>
import Draggable from 'vuedraggable';
import SourceIcon from "@/Pages/Sources/Components/SourceIcon.vue";
import Tippy from "@/Components/Tippy.vue";
import {Link, useForm} from '@inertiajs/vue3';
import {computed} from "vue";
import { router } from '@inertiajs/vue3'

const props = defineProps({
    plans: {
        type: Array,
        required: true,
    },
    campaign: {
        type: Object,
        required: true,
    },
});

const plansProxy = computed({
    get: () => props.plans,
    set: (val) => {
        let order = val.map((plan) => plan.id);
        // console.log(order);
        useForm({order})
            .put(route('campaign.planfact.plans-order.update', props.campaign));
    }
});

const removePlan = (plan) => {
    const res = confirm('План факт будет удален. Продолжить?');
    if (res)
        return router.delete(route('campaign.planfact.delete', {
            campaign: props.campaign.id,
            planSettings: plan.id,
        }));

    return null;
}


function arrToUl(arr) {
    return `<ul><li>- ${arr.join('</li><li>- ')}</li></ul>`;
}
</script>

<style scoped>
.planfact-title {
    font-weight: 900;
    font-size: 16px;
    line-height: 20px;
    letter-spacing: 0.2px;
    color: #252733;
}
</style>
