<template>
    <private-layout :loyalties="loyalties" :loyalty="loyalty">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <form class="space-y-4 relative" @submit.prevent="onSubmit">

                <div class="space-y-2">
                    <label class="text-sm">Настройки</label>
                    <text-input
                        class="form-control-sm"
                        v-model.trim="form.title"
                        placeholder="Заголовок"
                        required
                    />
                    <text-input
                        class="form-control-sm"
                        v-model.trim="form.desc"
                        placeholder="Описание"
                        required
                    />
                </div>

                <image-field
                    v-model="form.logo"
                    v-model:deleted="form.logo_del"
                    :src="loyalty?.card_logo_url ?? null"
                    :accept="['image/png', 'image/jpeg']"
                >
                    Логотип...
                </image-field>

                <image-field
                    v-model="form.banner"
                    v-model:deleted="form.banner_del"
                    :src="loyalty?.card_banner_url ?? null"
                    :accept="['image/png', 'image/jpeg']"
                >
                    Баннер...
                </image-field>

                <div>
                    <label class="text-sm">Цвет фона</label>
                    <hex-color-picker v-model="form.header_color" required/>
                </div>

                <div class="flex flex-col gap-y-2">
                    <toggle-switch v-model:checked="form.show_name">Имя</toggle-switch>
                    <toggle-switch v-model:checked="form.show_balance">Баланс</toggle-switch>
                    <toggle-switch v-model:checked="form.show_next_visit">Следующий визит</toggle-switch>
                    <toggle-switch v-model:checked="form.show_discount">Размер скидки</toggle-switch>
                </div>

                <div class="form-field">
                    <label class="form-label">Размер скидки по умолчанию</label>
                    <text-input
                        class="form-control-sm"
                        type="number"
                        v-model.number="form.discount_percent"
                        min="0"
                        max="100"
                    />
                </div>

                <hr/>

                <validation-errors/>

                <p class="mt-2 text-sm text-gray-600">Настройки в Google Wallet обновляются раз в 2 минуты.</p>
                <button class="btn btn-md btn-primary" type="submit">
                    Сохранить
                </button>
            </form>
            <div class="flex flex-col items-center">
                <div class="sm:mx-auto sm:w-[400px] w-full space-y-8">
                    <loyalty-card :loyalty="loyaltyWithSettings" inactive/>
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
import ImageField from "@/Pages/Reviews/Components/ImageField.vue";
import {computed} from "vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import LoyaltyCard from "@/Pages/Loyalty/Components/Cards/LoyaltyCard.vue";

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
    title: '',
    desc: '',

    header_color: '',

    logo: null,
    logo_del: false,
    banner: null,
    banner_del: false,

    show_name: false,
    show_balance: false,
    show_next_visit: false,
    show_discount: false,

    discount_percent: 0,

    ...props.loyalty.card_settings,

    _method: 'put',
});

const loyaltyWithSettings = computed(() => {return {...props.loyalty, card_settings: form.data()}});

function onSubmit() {
    form.post(route('loyalty.private.loyalty.card-settings.save', [props.loyalty.project_id, props.loyalty.id]));
}
</script>
