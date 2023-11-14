<template>
    <authenticated>
        <div class="flex flex-row flex-nowrap">
            <div
                v-for="(tabProps, tabKey) in pageTabs"
                @click="selectedTab = tabKey"

                class="px-3 py-1 border-primary cursor-pointer"
                :class="{'border-b-2': selectedTab === tabKey}"
            >
                {{ tabProps.title }}
            </div>
        </div>

        <div class="mt-4 w-full">
            <!-- Сделать отдельными страницами, а это как layout -->
            <component
                v-if="pageTabs[selectedTab].component"
                :is="pageTabs[selectedTab].component"
                :projects="projects"
                :project-visits="campaignsVisits"
                :balance="balance"
            />
        </div>
    </authenticated>

    <page-title lang="users.new_payment"/>

    <div class="md:w-5/6 w-full max-w-[1080px]">
        <div class="mb-8">
            <h2 class="text-lg font-bold">Тарифы</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3">
                <plan-card v-for="plan in plans" :plan="plan" class="w-auto"/>
            </div>
        </div>

        <hr>

        <div class="mb-8 mt-4 max-w-[1080px]">
            <h2 class="text-lg font-bold">Проекты</h2>

            <div class="overflow-x-auto">
                <table class="table-plan-campaigns">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Название</th>
                        <th>Визиты</th>
                        <th>Тариф</th>
                        <th>Стоимость</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>

                    <template v-if="subscriptions.length > 0">

                        <template v-for="sub in subscriptions" :key="sub.id">
                            <tr>
                                <td>{{ sub.campaign.id }}</td>
                                <td>{{ sub.campaign.name }}</td>
                                <td>
                                    <span>{{ separateNumber(campaignsVisits[n(sub.campaign.id)] ?? 0) }}</span>
                                    /
                                    <span v-if="sub.plan.visits_limit">{{ separateNumber(sub.plan.visits_limit) }}</span>
                                    <span v-else>∞</span>
                                </td>
                                <td>
                                    <Link :href="route('campaign.subscriptions.choose.show', sub.campaign.id)"
                                          class="link flex flex-row flex-nowrap items-center"
                                    >
                                        <img src="/icons/edit.svg" alt="edit" class="mr-1" />
                                        <span>{{ sub.plan.name }}{{ sub.plan.status === 'archived' ? '*' : '' }}</span>
                                    </Link>
                                </td>
                                <td>{{ separateNumber(r(sub.plan.formatted_price / 30, 0, 'c')) }} ₽/день</td>
                            </tr>
                        </template>
                    </template>
                    <tr v-else>
                        <td colspan="99" class="text-gray-500 text-center">*Нет активных подписок*</td>
                    </tr>

                    <tr class="font-bold">
                        <td>Всего</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ separateNumber(r(monthExpenses / 30, 0, 'c')) }} ₽/день</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <p v-if="hasArchivedPlan" class="text-xs text-gray-500 mt-1 ml-2">
                <span class="text-sm">*</span> Архивный план, в настоящее время недоступен для подключения.
            </p>
        </div>

        <div class="mb-8 grid md:grid-cols-2 lg:gap-x-32 md:gap-x-16 gap-x-4 gap-y-4">

            <div>
                <h2 class="text-lg font-bold">Оплата</h2>
                <p>Пользователь: {{ auth.user.email }}</p>
                <div class="mb-4">
                    <label v-for="type in paymentTypes" class="block text-sm mb-1">
                        <input
                            type="radio"
                            v-model="paymentType"
                            :value="type"
                            class="mr-2"
                        >
                        {{ $t('common.paymentTypes.' + type) }}
                    </label>
                </div>

                <div v-if="paymentType === 'invoice'" class="mb-2">
                    <div class="grid sm:grid-cols-2 grid-cols-1">
                        <div class="form-field mb-2 pr-2">
                            <!--                            <label class="form-label">Телефон для связи</label>-->
                            <text-input
                                v-model:modelValue="invoiceForm.phone"
                                type="tel"
                                placeholder="Телефон для связи"
                                required
                                autocomplete="tel"
                                :error="invoiceForm.errors.phone"
                                mask="tel"
                            ></text-input>
                        </div>
                        <div class="form-field mb-2">
                            <!--                            <label class="form-label">E-Mail</label>-->
                            <text-input
                                v-model:modelValue="invoiceForm.email"
                                type="email"
                                placeholder="Электронная почта"
                                required
                                autocomplete="email"
                                :error="invoiceForm.errors.email"
                            ></text-input>
                        </div>
                    </div>
                    <div class="form-field mb-2">
                        <!--                        <label class="form-label">Название компании или ИНН</label>-->
                        <text-input
                            v-model:modelValue="invoiceForm.company_name"
                            type="text"
                            placeholder="Название компании или ИНН"
                            required
                            autocomplete="company"
                            :error="invoiceForm.errors.company_name"
                        ></text-input>
                    </div>
                </div>
                <div class="flex flex-row flex-nowrap justify-between text-xs py-2">
                    <checkbox v-model="enableAutoRefill" v-if="false">Включить автопополнение</checkbox>
                    <span class="text-gray-500 cursor-pointer" @click="showDiscountCodeModal = true">
                        <template v-if="isEmpty2(appliedDiscountCode)">Есть промокод?</template>
                        <tippy :content="discountCodeDesc"
                               v-else
                        >Применён промокод `{{ appliedDiscountCode.code }}`.</tippy>
                    </span>
                </div>
                <hr>
                <p class="text-xs p-1">
                    При оплате вы принимаете
                    <a href="https://dailygrow.ru/oferta.pdf/" target="_blank" class="link">Лицензионное соглашение</a>
                </p>
            </div>

            <div>
                <div class="pb-4">
                    <p class="text-lg font-bold mt-2">Баланс: {{ balance }} ₽</p>
                    <p class="text-sm" v-if="!isEmpty(expensesPrediction)">
                        Текущего баланса хватит на ~{{ expensesPrediction }} дней
                    </p>

                    <form class="py-4" @submit.prevent="onSubmit">
                        <input type="number" class="form-control" placeholder="5000" v-model="amount"/>

                        <p v-if="!isEmpty2(amount) && !isEmpty2(appliedDiscountCode)"
                           class="text-xs py-2"
                        >
                            <span>{{ amount }} ₽</span>
                            <span v-if="!isEmpty2(percentAmount)"> + {{ percentAmount }} ₽ ({{ appliedDiscountCode.percent }} %)</span>
                            <span v-if="!isEmpty2(amountAmount)"> + {{ amountAmount }} ₽</span>
                            <span class="font-bold"> = {{ finalAmount }} ₽</span>
                        </p>

                        <button class="btn btn-md btn-primary-dark mt-4 w-full">Пополнить баланс</button>
                    </form>
                </div>
            </div>
        </div>

        <modal :show="showDiscountCodeModal"
               :closeable="true"
               max-width="sm"
               @close="showDiscountCodeModal = false"
        >
            <form @submit.prevent="onDiscountCodeSubmit" class="p-4">
                <div class="form-field mb-4 font-bold">
                    <label class="form-label">Промокод</label>
                    <text-input v-model.trim="discountCodeForm.discount_code"
                                placeholder="Промокод"
                                :error="discountCodeForm.errors.discount_code"
                                required
                    />
                </div>
                <button class="btn btn-md btn-primary w-full" type="submit">Применить</button>
            </form>
        </modal>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import PlanCard from "@/Pages/Billing/Components/PlanCard.vue";
import {isEmpty, isEmpty2, r, separateNumber, n} from '@/utils';
import TextInput from "@/Components/Forms/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {router, useForm, Link} from "@inertiajs/vue3";
import PageTitle from "@/Components/PageTitle.vue";
import Modal from "@/Components/Modal/Modal.vue";
import Tippy from "@/Components/Tippy.vue";
import Authenticated from "@/Layouts/Authenticated.vue";
import PaymentTab from "@/Pages/Billing/Tabs/PaymentTab.vue";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
    plans: {
        type: Array,
        required: true,
    },
    subscriptions: {
        type: Array,
        required: true,
    },
    campaignsVisits: {
        type: Object,
        required: true,
    },
    appliedDiscountCode: {
        type: [Object, null],
        required: false,
        default: null,
    },
    projects: {
        type: Array,
        required: true,
    },
});

const pageTabs = {
    payment: {
        title: 'Подписка и оплата',
        component: PaymentTab,
    },
    history: {
        title: 'История операций',
        component: null,
    },
    contracts: {
        title: 'Договора',
        component: null,
    },
    acts: {
        title: 'Акты',
        component: null,
    },
};
const selectedTab = ref('payment');

const paymentTypes = [
    'payment',
    'invoice',
];
const paymentType = ref('payment');
const enableAutoRefill = ref(true);
const amount = ref();
const discountCodeForm = useForm({
    discount_code: props.appliedDiscountCode?.code ?? '',
});

const invoiceForm = useForm({
    phone: '+7',
    email: '',
    company_name: '',
    formatted_amount: 0,
});

const showDiscountCodeModal = ref(false);

const subsAccordionItem = ref();
function onAccordionItemClick(item) {
    if (subsAccordionItem.value === item) {
        subsAccordionItem.value = null;
    } else {
        subsAccordionItem.value = item;
    }
}

function onDiscountCodeSubmit() {
    discountCodeForm.post(route('user.billing.new-payment.discount-code.apply'), {
        onSuccess: () => {
            showDiscountCodeModal.value = false;
            router.reload({only: ['appliedDiscountCode']});
        },
    });
}

const onSubmit = async () => {
    switch (paymentType.value) {
        case 'payment': {
            location.href = (await axios.post(route('user.billing.new-payment.create', {amount: amount.value})))
                .data
                .confirmation_url;
            break;
        }
        case 'invoice': {
            invoiceForm.formatted_amount = amount.value;
            invoiceForm.post(route('user.billing.new-payment.create-invoice'));
            invoiceForm.reset();
            break;
        }
    }
}

const discountCodeDesc = computed(() => {
    if (isEmpty2(props.appliedDiscountCode)) {
        return '';
    }

    let res = [];

    if (!isEmpty2(props.appliedDiscountCode.percent)) {
        res.push(`+ ${props.appliedDiscountCode.percent}%`);
    }

    if (!isEmpty2(props.appliedDiscountCode.amount)) {
        res.push(`+ ${amountAmount.value} ₽`);
    }

    return res.join(', ');
});

const finalAmount = computed(() => {
    if (isEmpty2(props.appliedDiscountCode)) {
        return amount.value;
    }

    return n(amount.value + percentAmount.value + amountAmount.value);
});

const percentAmount = computed(() => {
    if (isEmpty2(props.appliedDiscountCode.percent)) {
        return 0;
    }

    return r((amount.value * props.appliedDiscountCode.percent) / 100, 0);
});

const amountAmount = computed(() => r((props.appliedDiscountCode?.amount ?? 0) / 1000, 0));

const hasArchivedPlan = computed(() => {
    for (let i in props.subscriptions) {
        if (props.subscriptions[i].plan.status === 'archived') {
            return true;
        }
    }
    return false;
});

const monthExpenses = computed(() => {
    let res = 0;
    for (let i = 0; i < props.subscriptions.length; i++) {
        res += props.subscriptions[i].plan.formatted_price;
    }
    return res;
});

const expensesPrediction = computed(() => {
    if (monthExpenses.value < 1) {
        return null;
    }

    let dayExpenses = monthExpenses.value / 30;

    return r(balance.value / dayExpenses, 0, 'f');
});

const balance = computed(() => {
    return r(props.auth.balance / 1000, 2);
});
</script>
