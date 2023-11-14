<template>
    <private-layout :loyalties="loyalties" :loyalty="loyalty">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <form class="space-y-4 relative" @submit.prevent="onSubmit">
                <loading-block spinner-dark class="bg-gray-500/10" paddings v-if="form.processing" />

                <div class="space-y-2">
                    <h3 class="font-semibold">О компании</h3>
                    <text-input
                        placeholder="Название компании"
                        class="form-control-sm"
                        v-model.trim="form.company_name"
                        required
                    />
                    <text-input
                        placeholder="Описание"
                        class="form-control-sm"
                        v-model.trim="form.company_desc"
                        required
                    />
                </div>

                <image-field
                    v-model="form.logo"
                    v-model:deleted="form.logo_del"

                    :src="loyalty?.form_logo_url ?? null"
                    :accept="['image/png', 'image/jpeg']"
                >
                    Какой-то текст. Например, рекомендация по соотношению сторон логотипа.
                </image-field>

                <div class="space-y-2">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div>
                            <label class="text-sm">Цвет фона</label>
                            <hex-color-picker v-model="form.header_color" required/>
                        </div>
                        <div>
                            <label class="text-sm">Цвет кнопки</label>
                            <hex-color-picker v-model="form.button_color" required/>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="font-semibold">Анкета</h3>
                    <text-input
                        placeholder="Заголовок"
                        class="form-control-sm"
                        v-model="form.title"
                        required
                    />
                    <text-input
                        placeholder="Текст кнопки"
                        class="form-control-sm"
                        v-model="form.button_text"
                        required
                    />

                    <hr/>

                    <div
                        v-for="(title, key) in HARDCODE_FIELDS_KEYS"
                        :key="key"
                        class="flex flex-row items-center gap-x-2"
                    >
                        <text-input
                            :placeholder="title"
                            class="form-control-sm"
                            v-model.trim="form[key].title"
                            required
                        />
                        <tippy content="Обязательно для заполнения">
                            <toggle-switch v-model:checked="form[key].required" />
                        </tippy>
                    </div>
                    <hr/>
                    <div
                        v-for="(ignored, index) in form.custom_fields"
                        :key="index"
                        class="flex flex-row items-center gap-x-2"
                    >
                        <text-input
                            placeholder="Название поля"
                            class="form-control-sm"
                            v-model.trim="form.custom_fields[index].title"
                            required
                        />
                        <text-input
                            placeholder="Индекс поля"
                            class="form-control-sm"
                            v-model.trim="form.custom_fields[index].key"
                            required
                        />
                        <tippy content="Обязательно для заполнения">
                            <toggle-switch v-model:checked="form.custom_fields[index].required" />
                        </tippy>
                        <button @click="onRemoveCustomField(index)" type="button">
                            <img alt="del" src="/icons/cross-circle.svg" class="w-8">
                        </button>
                    </div>
                    <button
                        @click="onAddCustomField"
                        class="btn btn-md btn-outline-base"
                        type="button"
                    >Добавить поле</button>
                </div>

                <hr/>

                <div class="space-y-2">
                    <h3 class="font-semibold">Политика конфиденциальности</h3>
                    <checkbox class="text-xs" v-model:checked="form.terms_required">
                        Согласие с программой лояльности передачей информации
                    </checkbox>
                    <checkbox class="text-xs" v-model:checked="form.email_required">
                        Согласие на получение E-Mail рассылок
                    </checkbox>
                    <checkbox class="text-xs" v-model:checked="form.sms_required">
                        Согласие на получение SMS рассылок
                    </checkbox>

                    <label class="text-xs">
                        <span>Своя политика конфиденциальности</span>
                        <text-input
                            class="form-control-sm"
                            placeholder="Ссылка на политику конфиденциальности"
                            v-model.trim="form.custom_terms"
                        />
                    </label>
                </div>

                <hr/>

                <validation-errors/>

                <button class="btn btn-md btn-primary" type="submit">
                    Сохранить
                </button>
            </form>
            <div class="flex flex-col items-center">
                <div class="sm:mx-auto sm:w-[400px] w-full space-y-8">
                    <login-card :loyalty="loyaltyWithSettings" inactive/>
                    <phone-verify-card :loyalty="loyaltyWithSettings" inactive/>
                    <reg-card :loyalty="loyaltyWithSettings" inactive/>
                </div>
            </div>
        </div>
    </private-layout>
</template>

<script setup>
import PrivateLayout from "@/Pages/Loyalty/Layouts/PrivateLoyaltyLayout.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import HexColorPicker from "@/Pages/SocialWidget/Components/HexColorPicker.vue";
import {useForm} from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import ImageField from "@/Pages/Reviews/Components/ImageField.vue";
import LoginCard from "@/Pages/Loyalty/Components/Cards/LoginCard.vue";
import {computed} from "vue";
import PhoneVerifyCard from "@/Pages/Loyalty/Components/Cards/PhoneVerifyCard.vue";
import RegCard from "@/Pages/Loyalty/Components/Cards/RegCard.vue";
import Tippy from "@/Components/Tippy.vue";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    loyalties: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    company_name: '',
    company_desc: '',

    header_color: '',
    button_color: '',

    logo: null,
    logo_del: false,

    title: '',
    button_text: '',

    name_field: {
        title: '',
        required: false,
    },
    surname_field: {
        title: '',
        required: false,
    },
    email_field: {
        title: '',
        required: false,
    },
    birthday_field: {
        title: '',
        required: false,
    },
    gender_field: {
        title: '',
        required: false,
    },

    terms_required: false,
    sms_required: false,
    email_required: false,
    custom_terms: '',

    custom_fields: [],

    ...props.loyalty.form_settings,

    _method: 'put',
});

const HARDCODE_FIELDS_KEYS = {
    name_field: 'Имя',
    surname_field: 'Фамилия',
    email_field: 'E-Mail',
    birthday_field: 'День рождения',
    gender_field: 'Пол',
};

const loyaltyWithSettings = computed(() => {return {...props.loyalty, form_settings: form.data()}});

function onSubmit() {
    form.post(route('loyalty.private.loyalty.form-settings.save', [props.loyalty.project_id, props.loyalty.id]));
}

function onAddCustomField() {
    form.custom_fields.push({
        title: '',
        key: '',
    });
}

function onRemoveCustomField(index) {
    form.custom_fields.splice(index, 1);
}
</script>
