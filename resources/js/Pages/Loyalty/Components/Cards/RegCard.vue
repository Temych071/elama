<template>
    <card-block
        :header-bg="loyalty.form_settings.header_color"
        :inactive="!freeCardsExists"
        inactive-text="На данный момент регистрация недоступна. Попробуйте позже."
    >
        <template #header v-if="!isEmpty2(loyalty.form_logo_url)">
            <div class="flex items-center justify-center p-8">
                <img
                    alt="logo"
                    :src="loyalty.form_logo_url"
                    class="object-fit max-w-full"
                />
            </div>
        </template>
        <form class="px-5 pt-4 pb-12" @submit.prevent="onSubmit">
            <h1 class="text-center font-bold text-2xl px-4">Получите карту Лояльности</h1>
            <div class="mt-4 flex flex-col gap-y-2">
                <input
                    v-model.trim="form.name"
                    class="form-control border"

                    :placeholder="getFieldTitle(loyalty.form_settings.name_field)"
                    :required="loyalty.form_settings.name_field.required"
                />
                <input
                    v-model.trim="form.surname"
                    class="form-control border"

                    :placeholder="getFieldTitle(loyalty.form_settings.surname_field)"
                    :required="loyalty.form_settings.surname_field.required"
                />
                <input
                    v-model.trim="form.email"
                    class="form-control border"

                    :placeholder="getFieldTitle(loyalty.form_settings.email_field)"
                    :required="loyalty.form_settings.email_field.required"
                />
                <div class="relative">
                    <input
                        :value="formattedBirthday"
                        class="form-control border cursor-pointer"

                        :placeholder="getFieldTitle(loyalty.form_settings.birthday_field)"
                        :required="loyalty.form_settings.birthday_field.required"
                        readonly
                        @click.stop="showBirthdayPicker = !showBirthdayPicker"
                        ref="birthdayFieldEl"
                    />
                    <div :hidden="!showBirthdayPicker" class="absolute z-[9999]">
                        <v-date-picker
                            ref="birthdayPickerEl"
                            mode="date"
                            :max-date="new Date()"
                            v-model="form.birthday"
                            :locale="getLocale()"
                        />
                    </div>
                </div>
                <searchable-select
                    v-model="form.gender"
                    :options="[
                        {
                            title: 'Мужской',
                            value: 'male',
                        },
                        {
                            title: 'Женский',
                            value: 'female',
                        },
                        // { // :))
                        //     title: 'Другой',
                        //     value: 'other',
                        // },
                    ]"
                    input-classes="form-control border"
                    :placeholder="getFieldTitle(loyalty.form_settings.gender_field)"
                    :required="loyalty.form_settings.gender_field.required"
                />
                <input
                    v-for="field in loyalty.form_settings.custom_fields"
                    v-model.trim="form[field.key]"
                    class="form-control border"

                    :placeholder="getFieldTitle(field)"
                    :required="field.required"
                />
                <div class="flex flex-col gap-y-2">
                    <checkbox
                        v-if="loyalty.form_settings.terms_required"

                        class="text-xs"
                        v-model:checked="form.terms_accepted"
                    >
                        Я принимаю условия <a :href="policyUrl" target="_blank" class="link">программы лояльности и
                        передачи информации</a>
                    </checkbox>
                    <checkbox
                        v-if="loyalty.form_settings.sms_required"

                        class="text-xs"
                        v-model:checked="form.sms_notifications"
                    >
                        Я соглашаюсь получать маркетинговые SMS рассылки
                    </checkbox>
                    <checkbox
                        v-if="loyalty.form_settings.email_required"

                        class="text-xs"
                        v-model:checked="form.email_notifications"
                    >
                        Я соглашаюсь получать маркетинговые email рассылки
                    </checkbox>
                </div>
            </div>
            <validation-errors/>
            <button
                class="btn text-white btn-md w-full mt-8"
                type="submit"
                :style="`background-color: ${loyalty.form_settings.button_color}`"
            >{{ loyalty.form_settings.button_text }}</button>
        </form>
    </card-block>
</template>

<script setup>
import CardBlock from "@/Pages/Loyalty/Components/CardBlock.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import {getLocale, isEmpty2} from "@/utils";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    freeCardsExists: {
        type: Boolean,
        required: false,
        default: true,
    },
    latestForm: {
        type: Object,
        required: false,
        default: null,
    },
    inactive: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const birthdayFieldEl = ref(null);
const birthdayPickerEl = ref(null);
const showBirthdayPicker = ref(false);
document.addEventListener('click', event => {
    if (event.target !== birthdayFieldEl && event.target !== birthdayPickerEl) {
        showBirthdayPicker.value = false;
    }
});

const form = useForm({
    name: '',
    surname: '',
    email: '',
    birthday: null,
    gender: null,
    sms_notifications: true,
    email_notifications: true,
    terms_accepted: true,

    ...(() => props.loyalty.form_settings.custom_fields.reduce((acc, val) => {
        acc[val] = '';
        return acc;
    }, {}))(),

    ...(props.latestForm ?? {}),
});

function getFieldTitle(field) {
    return `${field.title}${field.required ? '*' : ''}`;
}

const policyUrl = computed(() => isEmpty2(props.loyalty.form_settings.custom_terms)
    ? route('reviews.public.policy')
    : props.loyalty.form_settings.custom_terms);

function onSubmit() {
    if (props.inactive) {
        return;
    }

    form.post(route('loyalty.public.form.send', props.loyalty.id));
}

const formattedBirthday = computed(() => isEmpty2(form.birthday) ? '' : (new Date(form.birthday)).toLocaleDateString());
</script>
