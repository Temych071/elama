<template>
    <authenticated>
        <div class="grid md:grid-cols-3 grid-cols-1 w-full gap-6 mb-6">
            <div class="space-y-6 md:col-span-2">
                <div class="pcard min-h-full">
                    <h2 class="pcard-header">Текущая подписка</h2>

                    <table class="table-plan-campaigns mt-2">
                        <thead>
                        <tr>
                            <th></th>
                            <th>№</th>
                            <th>Название</th>
                            <th>Лимиты</th>
                            <th>Тариф</th>
                            <th>Стоимость</th>
                        </tr>
                        </thead>

                        <tbody>
                        <template v-if="projects.length > 0">
                            <tr v-for="project in projectsList" :key="project.id">
                                <td class="whitespace-nowrap">
                                    <input
                                        type="radio"
                                        v-model="selectedProjectId"
                                        :value="project.id"
                                        class="mb-0.5"
                                    />
                                </td>
                                <td class="whitespace-nowrap">{{ project.id }}</td>
                                <td>{{ project.name }}</td>
                                <td class="flex flex-col whitespace-nowrap">
                                    <div v-if="project.hasSub" class="flex flex-col">
                                        <div v-for="(limitData, limitKey) in project.limits" :key="limitKey">
                                            {{ limitData.current }} / {{ limitData.max }}
                                        </div>
                                    </div>
                                    <template v-else>
                                        -
                                    </template>
                                </td>
                                <td class="whitespace-nowrap"
                                    :class="`${project.hasSub ? 'font-bold' : ''} ${isSubNotActive(project) ? 'text-warning' : 'text-primary'}`"
                                    :title="`${isSubNotActive(project) ? 'Подписка приостановлена.' : ''}`"
                                >
                                    {{ project.planName }}{{ project.planArchived ? '*' : '' }}
                                </td>
                                <td class="whitespace-nowrap">{{ project.costPerMonth }}<span v-if="project.hasSub"> ₽/мес</span>
                                </td>
                            </tr>
                        </template>
                        <tr v-else>
                            <td colspan="99" class="text-gray-500 text-center">*Проекты не найдены*</td>
                        </tr>

                        <tr class="font-bold">
                            <td></td>
                            <td>Всего</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="whitespace-nowrap">{{ separateNumber(costPerMonth) }} ₽/мес</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-5">
                <div class="pcard">
                    <loading-block
                        v-if="paymentForm.processing"
                        spinner-dark
                        class="bg-gray-500/20 items-center"
                    />

                    <div class="flex flex-row flex-nowrap">
                        <h2 class="pcard-header">Оплата</h2>
                        <div class="flex-grow"></div>
                        <p class="pcard-right-header">{{ auth.user.email ?? '-' }}</p>
                    </div>

                    <div class="mt-2">
                        <div>
                            <label
                                v-for="(typeData, type) in paymentTypes"
                                :key="type"
                                class="block text-sm flex items-center py-1 cursor-pointer"
                            >
                                <input
                                    type="radio"
                                    :value="type"
                                    v-model="paymentForm.type"
                                    class="mr-1"
                                />
                                {{ typeData.title }}
                            </label>
                        </div>

                        <hr class="my-2">

                        <div class="flex justify-between items-center">
<!--                            <toggle-switch>Авто-пополнение</toggle-switch>-->
                            <button
                                type="button"
                                class="link text-xs"
                                @click="showAutoRefillSettingsModal = true"
                            >Настройки автоматического пополнения</button>
                        </div>

                        <modal
                            :show="showAutoRefillSettingsModal"
                            @close="showAutoRefillSettingsModal = false"
                            closeable
                            max-width="md"
                        >
                            <auto-refill-settings-card/>
                        </modal>
                    </div>
                </div>

                <div class="pcard">
                    <loading-block
                        v-if="paymentForm.processing"
                        spinner-dark
                        class="bg-gray-500/20 items-center"
                    />

                    <h2 class="pcard-header">Баланс: {{ separateNumber(balance) }} ₽</h2>
                    <p class="pcard-subheader" v-if="costPerMonth > 0">Хватит на ~ {{ leftDaysPrediction }} дней</p>

                    <form
                        v-if="paymentForm.type === 'payment'"
                        class="grid md:grid-cols-3 grid-cols-1 mt-4 gap-2"
                        @submit.prevent="onSubmitPayment"
                    >
                        <input v-model.number="paymentForm.formatted_amount" class="col-span-2 form-control-sm"
                               type="text"/>
                        <button class="btn btn-md btn-primary">Пополнить</button>
                        <span
                            class="underline cursor-pointer text-xs text-gray-400"
                            @click="onShowPromocode"
                        >Ввести промокод</span>

                        <checkbox
                            v-model:checked="paymentForm.savePaymentMethod"
                            class="text-sm col-span-full text-gray-500"
                        >
                            Я даю согласие на регулярные списания, на обработку персональных данных и принимаю условия <a href="https://dailygrow.ru/oferta.pdf/" target="_blank" class="text-primary">публичной оферты</a>
                        </checkbox>
                        <p
                            v-if="paymentForm.savePaymentMethod"
                            class="text-yellow-500 col-span-full text-sm"
                        >Сохранение способа оплаты доступно только для банковских карт.</p>
                    </form>

                    <form
                        v-if="paymentForm.type === 'invoice'"
                        class="grid md:grid-cols-3 grid-cols-1 mt-4 gap-2"
                        @submit.prevent="onSubmitInvoice"
                    >
                        <text-input
                            v-model:modelValue="paymentForm.phone"
                            type="tel"
                            placeholder="Телефон для связи"
                            required
                            autocomplete="tel"
                            :error="paymentForm.errors.phone"
                            mask="tel"
                            class="col-span-full form-control-sm"
                        ></text-input>
                        <text-input
                            v-model:modelValue="paymentForm.email"
                            type="email"
                            placeholder="Электронная почта"
                            required
                            autocomplete="email"
                            :error="paymentForm.errors.email"
                            class="col-span-full form-control-sm"
                        ></text-input>
                        <text-input
                            v-model:modelValue="paymentForm.company_name"
                            type="text"
                            placeholder="Название компании или ИНН"
                            required
                            autocomplete="company"
                            :error="paymentForm.errors.company_name"
                            class="col-span-full form-control-sm"
                        ></text-input>

                        <input v-model.number="paymentForm.formatted_amount" class="col-span-2 form-control-sm"
                               type="text"/>
                        <button class="btn btn-md btn-primary">Пополнить</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-3 grid-cols-1 w-full gap-6">
            <div class="space-y-6 md:col-span-2">
                <div class="pcard min-h-full" v-if="!isEmpty2(selectedProject)">
                    <h2 class="pcard-header">Настройка тарифа</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 py-2">
                        <div
                            v-for="plan in plans"
                            class="pcard"
                        >
                            <label class="flex flex-row flex-nowrap cursor-pointer">
                                <h4 class="pcard-header">{{ plan.name }}</h4>
                                <div class="flex-grow"></div>
                                <input
                                    class="pcard-right-header"
                                    type="radio"
                                    :value="plan.id"
                                    v-model="selectedPlanId"
                                />
                            </label>
                            <p class="pcard-subheader">{{ separateNumber(plan.formatted_price) }} ₽ / мес</p>

                            <div class="text-xs my-4" v-if="false">
                                <p>{{ separateNumber(plan.visits_limit) }} визитов на сайт в месяц</p>
                                <p>{{ separateNumber(plan.review_forms_limit) }} филиал(ов) для отзывов</p>
                            </div>

                            <p class="text-xs my-4" v-html="plan.description"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="pcard" v-if="!isEmpty2(selectedProject)">
                    <h2 class="pcard-header">Ваша подписка</h2>

                    <hr class="my-4">
                    <p class="text-xl my-2">
                        <b>{{ separateNumber(selectedPlan?.formatted_price ?? 0) }} ₽</b> в месяц
                    </p>
                    <p class="text-sm">
                        Тариф: {{ selectedPlan?.name ?? '-' }}
                    </p>
                    <hr class="my-6">

                    <form-button method="PUT"
                                 :action="route('campaign.subscriptions.resume', {campaign: selectedProjectId})"
                                 v-if="isResumeSub"
                                 class="btn btn-md btn-primary w-full"
                                 :options="{preserveState: true, preserveScroll: true}"
                    >Возобновить
                    </form-button>
                    <button
                        class="btn btn-md btn-primary w-full"
                        :class="{disabled: selectedPlanId === selectedProjectPlan?.id}"
                        @click="onUpdatePlan"
                        v-else
                    >Обновить тариф
                    </button>

                    <p class="policy-text py-4">
                        Нажимая кнопку "Обновить тариф" вы соглашаетесь с <a class="link"
                                                                             href="https://dailygrow.ru/oferta.pdf/"
                                                                             target="_blank">условиями использования</a>
                    </p>

                    <button
                        type="button"
                        class="discount-code-btn"
                        v-if="false"
                    >
                        Есть промокод?
                    </button>
                </div>
            </div>
        </div>

        <modal
            v-model:show="showPromocodeModal"
            closeable
            max-width="sm"
            @close="showPromocodeModal = false"
        >
            <form class="p-4 pt-" @submit.prevent="onSendPromocode">
                <h3 class="font-bold text-lg">Ввести промокод</h3>
                <div class="form-field mt-6">
                    <text-input
                        v-model="promocodeForm.discount_code"
                        :error="promocodeForm.errors.discount_code"
                        placeholder="Промокод"
                    />
                </div>

                <button class="btn btn-md w-full btn-primary mt-4" type="submit">Применить</button>
            </form>
        </modal>
    </authenticated>
</template>

<script setup>
import {separateNumber, r, isEmpty2} from "@/utils.js";
import {useForm, usePage} from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import {computed, ref, watch} from "vue";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import Authenticated from "@/Layouts/Authenticated.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import FormButton from "@/Components/Forms/FormButton.vue";
import Modal from "@/Components/Modal/Modal.vue";
import AutoRefillSettingsCard from "@/Pages/Billing/Components/AutoRefillSettingsCard.vue";

const props = defineProps({
    projects: {
        type: Array,
        required: false,
        default: [],
    },
    projectVisits: {
        type: Object,
        required: false,
        default: {},
    },
    auth: {
        type: Object,
        required: true,
    },
    plans: {
        type: Array,
        required: true,
    },
});

const showAutoRefillSettingsModal = ref(false);

const selectedProjectId = ref(props.projects[0]?.id ?? null);
const selectedProject = computed(() =>
    props.projects
        .find((project) => project.id === selectedProjectId.value)
);
const selectedProjectPlan = computed(() =>
    selectedProject.value?.active_subscription?.plan ?? null
);

watch(selectedProjectPlan, () => {
    selectedPlanId.value = selectedProjectPlan.value?.id;
});

const showPromocodeModal = ref(false);
const promocodeForm = useForm({
    discount_code: '',
});

function onShowPromocode() {
    showPromocodeModal.value = true;
}

function onSendPromocode() {
    promocodeForm.post(route('user.billing.new-payment.discount-code.apply'), {
        preserveScroll: true,
        onFinish: () => {
            promocodeForm.reset();
        },
        onSuccess: () => {
            showPromocodeModal.value = false;
        },
    });
}

const projectsList = computed(() => {
    return props.projects.map((project) => {
        const sub = project.active_subscription ?? null;
        const plan = sub?.plan ?? null;
        const hasSub = !isEmpty2(sub);

        return {
            ...project,

            hasSub,
            planName: plan?.name ?? '-',
            planArchived: plan?.status === 'archived',
            costPerMonth: hasSub ? separateNumber(plan?.formatted_price) : '-',

            limits: hasSub ? {
                visits: {
                    current: separateNumber(props.projectVisits[project.id] ?? 0),
                    max: plan.visits_limit ? separateNumber(plan.visits_limit) : '∞',
                },
                review_forms: {
                    current: separateNumber(project.review_forms?.length ?? 0),
                    max: separateNumber(plan.review_forms_limit ?? 0),
                },
            } : null,
        };
    });
});

const costPerMonth = computed(() => {
    return props.projects
        .map((project) => project.active_subscription?.plan?.formatted_price ?? null)
        .filter((val) => !isEmpty2(val))
        .reduce((sum, cost) => sum + cost, 0);
});

const balance = computed(() => {
    return r(props.auth.balance / 1000, 2);
});

const leftDaysPrediction = computed(() => r(balance.value / (costPerMonth.value / 30), 0));

const paymentTypes = {
    payment: {
        title: 'Банковской картой',
    },
    invoice: {
        title: 'По расчётному счёту',
    },
};

const paymentForm = useForm({
    // payment, invoice
    type: 'payment',
    savePaymentMethod: false,

    formatted_amount: 5000,
    phone: '+7',
    email: '',
    company_name: '',
});

const page = usePage();

const selectedPlanId = ref(selectedProjectPlan.value?.id);
const selectedPlan = computed(() =>
    props.plans
        .find((plan) => plan.id === selectedPlanId.value)
);

function onSubmitPayment() {
    paymentForm.processing = true;
    axios.post(route('user.billing.new-payment.create', {
        amount: paymentForm.formatted_amount,
        savePaymentMethod: paymentForm.savePaymentMethod,
    })).then(({data}) => {
        location.href = data.confirmation_url;
    }).catch(() => {
        page.props.toast = {
            type: 'error',
            message: 'Не удалось создать счёт на оплату...',
        };
    }).finally(() => {
        paymentForm.processing = false;
    });
}

function onSubmitInvoice() {
    paymentForm.savePaymentMethod = false;
    paymentForm.post(route('user.billing.new-payment.create-invoice'));
}

function onUpdatePlan() {
    if (selectedProjectPlan.value?.id === selectedPlanId.value) {
        return;
    }

    if (!confirm('Для применения тарифа будет списана его дневная стоимость. Продолжить?')) {
        return;
    }

    useForm({
        plan_id: selectedPlanId.value,
        project_id: selectedProjectId.value,
    }).put(route('user.billing.new-payment.update-plan'), {
        preserveScroll: true,
        preserveState: true,
    });
}

const isResumeSub = computed(() => (
    selectedPlanId.value === selectedProjectPlan.value?.id
    && ['not-charged', 'paused'].includes(selectedProject.value?.active_subscription?.status)
));

function isSubNotActive(project) {
    return ['not-charged', 'paused'].includes(project?.active_subscription?.status);
}

</script>

<style scoped lang="scss">
.pcard {
    background: #FFFFFF;
    border: 1px solid #DFE0EB;
    box-shadow: 0 4px 6px #E5E5E5;
    border-radius: 6px;
    padding: 16px;
    width: 100%;
    position: relative;

    .pcard-header {
        font-weight: 600;
        font-size: 14px;
        line-height: 20px;
    }

    .pcard-right-header {
        font-size: 12px;
        line-height: 20px;
    }

    .pcard-subheader {
        font-weight: 400;
        font-size: 12px;
        line-height: 20px;
    }
}

.policy-text {
    @apply text-sm text-black/70;
    font-size: 10px;
    line-height: 10px;
    letter-spacing: 0.2px;
}

.discount-code-btn {
    color: rgba(82, 85, 100, 0.4);
    font-size: 10px;
    line-height: 10px;
    letter-spacing: 0.2px;
}
</style>
