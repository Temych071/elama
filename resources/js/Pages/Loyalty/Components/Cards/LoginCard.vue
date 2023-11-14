<template>
    <card-block :header-bg="loyalty.form_settings.header_color">
        <template #header v-if="!isEmpty2(loyalty.form_logo_url)">
            <div class="flex items-center justify-center p-8">
                <img
                    alt="logo"
                    :src="loyalty.form_logo_url"
                    class="object-fit max-w-full"
                />
            </div>
        </template>
        <form class="px-5 pt-4 pb-12 space-y-4" @submit.prevent="onSubmit">
            <h1 class="text-center font-bold text-2xl px-4">{{ loyalty.form_settings.title }}</h1>
            <text-input
                placeholder="Введите номер телефона"
                v-model.trim="form.phone"
                class="form-control border"
                mask="tel"
            />
            <button
                class="btn text-white btn-md w-full"
                type="submit"
                :style="`background-color: ${loyalty.form_settings.button_color}`"
            >{{ loyalty.form_settings.button_text }}</button>
        </form>
    </card-block>
</template>

<script setup>
import CardBlock from "@/Pages/Loyalty/Components/CardBlock.vue";
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import {isEmpty2} from "@/utils";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    inactive: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const form = useForm({
    phone: '+7',
});

function onSubmit() {
    if (props.inactive) {
        return;
    }

    console.log(form.data());
    form.post(route('loyalty.public.login.send', props.loyalty.id));
}
</script>
