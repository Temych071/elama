<template>
    <card-block :header-bg="loyalty.form_settings.header_color">
        <form class="px-5 pt-4 pb-12 space-y-6" @submit.prevent="onSubmit">
            <h1 class="text-center font-bold text-2xl">Подтверждение телефона</h1>
            <p class="text-center">
                Введите код подтверждения из SMS-
                сообщения, отправленного на номер
                <span>{{ phone ?? '+7910*****68' }}</span>
            </p>
            <div>
                <input
                    placeholder="Полученный код"
                    v-model.trim="form.code"
                    class="form-control border"
                    required
                />
                <button
                    class="text-xs text-primary"
                    @click="onCodeRequest"
                    type="button"
                >Запросить код повторно</button>
            </div>
            <validation-errors/>
            <button
                class="btn text-white btn-md w-full"
                type="submit"
                :style="`background-color: ${loyalty.form_settings.button_color}`"
            >{{ loyalty.form_settings.button_text }}
            </button>
        </form>
    </card-block>
</template>

<script setup>
import CardBlock from "@/Pages/Loyalty/Components/CardBlock.vue";
import {router, useForm} from "@inertiajs/vue3";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    phone: {
        type: String,
        required: false,
        default: null,
    },
    inactive: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const form = useForm({
    code: '',
});

function onSubmit() {
    if (props.inactive) {
        return;
    }

    console.log(form.data());
    form.post(route('loyalty.public.phone-verification.send', props.loyalty.id));
}

function onCodeRequest() {
    if (props.inactive) {
        return;
    }

    router.post(route('loyalty.public.phone-verification.resend-code', props.loyalty.id));
}
</script>
