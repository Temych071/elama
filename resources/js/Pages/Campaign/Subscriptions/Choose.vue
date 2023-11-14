<template>
    <div class="py-2 px-4">
        <h1 class="text-2xl font-bold mb-4">Выбор тарифа</h1>

        <div>
            <h2 class="font-bold">Текущий тариф</h2>

            <plan-card :plan="subscription.plan" v-if="!isEmpty(subscription)">
                <template #header>
                    <p class="py-0.5 text-sm text-center bg-gray-300 rounded-lg mb-2 text-gray-800"
                       v-if="subscription.is_paused"
                    >Приостановлен</p>
                </template>

                <template #footer>
                    <form-button method="PUT"
                                 :action="route('campaign.subscriptions.resume', {campaign: campaign.id})"
                                 class="w-full btn btn-md btn-info mt-2"
                                 v-if="['not-charged', 'paused'].includes(subscription.status)"
                    >Возобновить
                    </form-button>

                    <form-button confirm="Вы действительно хотите отменить подписку?"
                                 method="DELETE"
                                 :action="route('campaign.subscriptions.choose.delete', {campaign: campaign.id})"
                                 class="w-full underline text-gray-500 mt-2"
                    >Отменить подписку
                    </form-button>
                </template>
            </plan-card>
            <p class="text-gray-500" v-else>*Подписка не оформлена*</p>
        </div>

        <div class="mt-8">
            <h2 class="font-bold">Тарифы</h2>

            <div class="flex flex-row flex-wrap flex-wrap justify-start">
                <plan-card v-for="plan in plans" :plan="plan">
                    <template #footer>
                        <form-button confirm="Для оформления подписки будет списана дневная плата за тариф. Продолжить?"
                                     :data="{plan_id: plan.id}"
                                     method="POST"
                                     :action="route('campaign.subscriptions.choose.store', {campaign: campaign.id})"
                                     class="w-full btn btn-md btn-info-dark mt-2"
                                     :disabled="plan.id === subscription?.plan?.id"
                        >Выбрать тариф
                        </form-button>
                    </template>
                </plan-card>
            </div>
        </div>
    </div>
</template>

<script setup>
import PlanCard from "@/Components/Subscriptions/PlanCard.vue";
import FormButton from "@/Components/Forms/FormButton.vue";
// import {onBeforeMount} from "vue";
import {isEmpty} from '@/utils';

const props = defineProps({
    plans: {
        type: Array,
        required: true,
    },
    campaign: {
        type: Object,
        required: true,
    },
    subscription: {
        type: [Object, null],
        required: true,
    },
});

// onBeforeMount(() => {
//     console.log(props.subscription);
// });

</script>

<script>
import layout from "@/Layouts/Authenticated.vue";

export default {layout};
</script>
