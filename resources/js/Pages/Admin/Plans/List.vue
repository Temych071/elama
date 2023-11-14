<template>
    <Head>
        <title>{{ $t('admin.plans.list.title') }}</title>
    </Head>

    <div>
        <h1 class="text-2xl font-bold mb-4">{{ $t('admin.plans.list.header') }}</h1>

        <div v-for="plan in plans" class="card px-6 mb-3">
            <h3 class="max-w-md mb-2 font-black text-lg">{{ plan.name }}</h3>

            <div v-if="!isEmpty(plan.description)" v-html="plan.description"></div>
            <p v-else class="text-gray-500 italic">
                *Без описания*
            </p>

            <div class="my-6 flex flex-row ">
                <div class="ml-2 border-l border-gray pl-2">
                    <h4 class="font-bold">{{ $t('admin.plans.list.valPrice') }}</h4>
                    <p class="mt-1">{{ plan.formatted_price }} ₽</p>
                </div>

                <div class="ml-2 border-l border-gray pl-2">
                    <h4 class="font-bold">{{ $t('admin.plans.list.valVisitsLimit') }}</h4>
                    <p class="mt-1" v-if="plan.visits_limit">{{ plan.visits_limit }}</p>
                    <p class="mt-1 w-full text-gray-500 italic" v-else>*Без ограничений*</p>
                </div>

                <div class="ml-2 border-l border-gray pl-2">
                    <h4 class="font-bold">Макс. кол-во форм:</h4>
                    <p class="mt-1" v-if="plan.review_forms_limit">{{ plan.review_forms_limit }}</p>
                    <p class="mt-1 w-full text-gray-500 italic" v-else>*Недоступно*</p>
                </div>

                <div class="ml-2 border-l border-gray pl-2">
                    <h4 class="font-bold">{{ $t('admin.plans.list.valStatus') }}</h4>
                    <p class="mt-1">{{ $t('admin.plans.statusNames.' + plan.status) }}</p>
                </div>
            </div>

            <div>
                <Link is="button"
                      class="btn btn-md btn-warning-dark"
                      :href="route('admin.plans.update', {plan: plan.id})"
                >{{ $t('admin.plans.list.btnEdit') }}
                </Link>

                <form-button is="button"
                             class="btn btn-md btn-error-dark ml-2"
                             :action="route('admin.plans.delete', {plan: plan.id})"
                             method="DELETE"
                             :confirm="`Вы действительно хотите удалить тариф '${plan.name}'?`"
                >{{ $t('admin.plans.list.btnDelete') }}
                </form-button>
            </div>
        </div>

        <form class="card px-4" @submit.prevent="onSubmit">
            <div class="grid grid-cols-2">
                <div>
                    <h3 class="title mb-4">
                        <text-input class="max-w-md" v-model.trim="addForm.name"
                                    :placeholder="$t('admin.plans.form.phName')"
                                    required/>
                    </h3>
                    <html-editor v-model="addForm.description" :placeholder="$t('admin.plans.form.phDescription')"/>

                    <div class="my-6 grid md:grid-cols-2 grid-cols-1 max-w-3xl gap-2">
                        <input type="number"
                               v-model.number="addForm.formatted_price"
                               class="form-control"
                               :placeholder="$t('admin.plans.form.phPrice')"
                               step="0.01"
                               required
                        />
                        <select class="select-sm" v-model="addForm.status" required>
                            <option :value="null">{{ $t('admin.plans.form.phStatus') }}</option>
                            <option v-for="status in statuses" :value="status">{{
                                    $t('admin.plans.statusNames.' + status)
                                }}
                            </option>
                        </select>

                        <input type="number"
                               v-model.number="addForm.visits_limit"
                               class="form-control"
                               :placeholder="$t('admin.plans.form.phVisitsLimit')"
                        />
                        <input type="number"
                               v-model.number="addForm.review_forms_limit"
                               class="form-control"
                               placeholder="Максимальное кол-во форм отзывов"
                        />
                    </div>
                </div>

                <checkbox-list :items="features"
                               v-model="addForm.features"
                               :title-key="(val) => $t('admin.plans.featureNames.' + val)"
                               select-all-label="Выбрать всё"
                               class="px-4"
                />
            </div>

            <button class="btn btn-md btn-success-dark w-full mt-4">{{ $t('admin.plans.list.btnCreate') }}</button>

            <validation-errors/>
        </form>
    </div>
</template>

<script setup>
import TextInput from "@/Components/Forms/TextInput.vue";
import {Link, useForm, Head} from "@inertiajs/vue3";
import FormButton from "@/Components/Forms/FormButton.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import CheckboxList from "@/Components/Forms/CheckboxList.vue";
import {isEmpty} from "@/utils";
import HtmlEditor from "@/Components/Forms/HtmlEditor.vue";

const props = defineProps({
    plans: {
        type: Array,
        required: true,
    },
    features: {
        type: Array,
        required: false,
        default: [],
    },
    statuses: {
        type: Array,
        required: false,
        default: ['active', 'archived'],
    },
});

const addForm = useForm({
    name: 'Новый тариф',
    description: null,
    formatted_price: null,
    visits_limit: null,
    review_forms_limit: null,
    status: null,
    features: [],
});

function onSubmit() {
    addForm.post(route('admin.plans.create'));
}

</script>

<script>
import layout from "@/Layouts/Admin.vue";

export default {layout};
</script>
