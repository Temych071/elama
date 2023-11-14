<template>
    <form class="card px-4" @submit.prevent="onSubmit">

        <div class="form-field mb-3">
            <input class="form-control"
                   v-model.trim="form.code"
                   placeholder="Код"
                   required
            />
        </div>

        <div class="form-field mb-3">
            <input v-model.trim="form.amount"
                   placeholder="Сколько руб начислить"
                   type="number"
                   class="form-control"
            />
        </div>

        <div class="form-field mb-3">
            <input v-model.trim="form.percent"
                   placeholder="Сколько % начислить"
                   type="number"
                   max="100"
                   class="form-control"
            />
        </div>

        <div class="form-field mb-3">
            <Checkbox v-model:checked="form.is_one_time">Одноразовый</Checkbox>
        </div>

        <div class="form-field mb-3">
            <Checkbox v-model:checked="form.is_active">Активен</Checkbox>
        </div>


        <button class="btn btn-md btn-primary-dark mt-4">Сохранить</button>
        <Link is="button"
              class="btn btn-md btn-gray-400 ml-2"
              type="button"
              :href="route('admin.discount-codes.list')"
        >Отменить
        </Link>

        <ValidationErrors/>
    </form>
</template>

<script setup>
import {Link, useForm} from "@inertiajs/vue3";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {isEmpty2} from "@/utils";
import {onMounted} from "vue";

const props = defineProps({
    code: {
        type: [Object, null],
        required: false,
        default: null,
    },
});

const form = useForm({
    code: null,
    amount: null,
    percent: null,
    is_one_time: false,
    is_active: true,

    ...isEmpty2(props.code) ? {} : {
        ...props.code,
        amount: props.code.amount ? props.code.amount / 1000 : null,
        is_one_time: Boolean(props.code.is_one_time),
        is_active: Boolean(props.code.is_active),
    },
});

function onSubmit() {
    if (isEmpty2(props.code)) {
        form.post(route('admin.discount-codes.store'));
    } else {
        form.put(route('admin.discount-codes.update', props.code.id));
    }
}
</script>

<script>
import layout from "@/Layouts/Admin.vue";

export default {layout};
</script>
