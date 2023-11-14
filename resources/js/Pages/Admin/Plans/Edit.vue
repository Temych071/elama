<template>
    <form class="card px-4" @submit.prevent="onSubmit">
        <div class="grid grid-cols-2">
            <div>
                <h3 class="title mb-4">
                    <text-input class="max-w-md" v-model.trim="form.name" :placeholder="$t('admin.plans.form.phName')"
                                required/>
                </h3>
                <html-editor v-model="form.description" :placeholder="$t('admin.plans.form.phDescription')"/>

                <div class="my-6 grid md:grid-cols-2 grid-cols-1 max-w-3xl gap-2">
                    <input type="number"
                           v-model.number="form.formatted_price"
                           class="form-control"
                           :placeholder="$t('admin.plans.form.phPrice')"
                           step="0.01"
                           required
                    />
                    <select class="select-sm" v-model="form.status" required>
                        <option :value="null">{{ $t('admin.plans.form.phStatus') }}</option>
                        <option v-for="status in statuses" :value="status">{{
                                $t('admin.plans.statusNames.' + status)
                            }}
                        </option>
                    </select>

                    <input type="number"
                           v-model.number="form.visits_limit"
                           class="form-control"
                           :placeholder="$t('admin.plans.form.phVisitsLimit')"
                    />
                    <input type="number"
                           v-model.number="form.review_forms_limit"
                           class="form-control"
                           placeholder="Максимальное кол-во форм отзывов"
                    />
                </div>
            </div>

            <checkbox-list :items="features"
                           v-model="form.features"
                           :title-key="(val) => $t('admin.plans.featureNames.' + val)"
                           select-all-label="Выбрать всё"
                           class="px-4"
            />
        </div>

        <button class="btn btn-md btn-primary-dark mt-4">{{ $t('admin.plans.edit.btnSave') }}</button>
        <Link is="button"
              class="btn btn-md btn-gray-400 ml-2"
              type="button"
              :href="route('admin.plans.list')"
        >{{ $t('admin.plans.edit.btnCancel') }}
        </Link>

        <validation-errors/>
    </form>
</template>

<script setup>
import {Link, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import CheckboxList from "@/Components/Forms/CheckboxList.vue";
import HtmlEditor from "@/Components/Forms/HtmlEditor.vue";

const props = defineProps({
    plan: {
        type: Object,
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

const form = useForm(props.plan);

function onSubmit() {
    form.put(route('admin.plans.update', {plan: props.plan.id}));
}
</script>

<script>
import layout from "@/Layouts/Admin.vue";

export default {layout};
</script>
