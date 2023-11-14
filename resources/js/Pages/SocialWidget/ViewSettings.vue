<template>
    <index
        :widgets="widgets"
        :project="project"
        :selected-widget="selectedWidget"
    >
        <div class="grid lg:grid-cols-2 grid-cols-1">
            <form class="space-y-4" @submit.prevent="onSubmit">
                <div class="card px-4 text-sm">
                    <h3 class="text-lg ">Положение виджета</h3>

                    <h4 class="form-label mt-3">Расположение виджета</h4>
                    <div class="space-x-4">
                        <label class="space-x-2">
                            <input type="radio" v-model="form.position" value="left">
                            <span class="text-sm">Слева</span>
                        </label>
                        <label class="space-x-2">
                            <input type="radio" v-model="form.position" value="right">
                            <span class="text-sm">Справа</span>
                        </label>
                    </div>

                    <hr class="my-4"/>

                    <div class="grid xl:grid-cols-2 grid-cols-1 gap-x-4">
                        <div>
                            <label class="form-label">Горизонтальный отступ</label>
                            <input
                                class="form-control-sm xl:w-auto w-full"
                                v-model="form.margin_x"
                                type="number"
                                required
                                min="0"
                            />
                        </div>
                        <div>
                            <label class="form-label">Вертикальный отступ</label>
                            <input
                                class="form-control-sm xl:w-auto w-full"
                                v-model="form.margin_y"
                                type="number"
                                required
                                min="0"
                            />
                        </div>
                    </div>
                </div>

                <div class="card px-4 text-sm z-30 relative">
                    <h3 class="text-lg">Оформление</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-4 gap-x-2 gap-y-4 mt-3">
                        <div>
                            <label class="form-label">Размер</label>
                            <div class="flex flex-col">
                                <label v-for="val in [40, 50, 64, 80]">
                                    <input type="radio" v-model="form.avatar_size" :value="val">
                                    {{ val }}x{{ val }}
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Вид</label>
                            <div class="flex flex-col">
                                <label v-for="item in ROOT_BTN_TYPES">
                                    <input type="radio" v-model="form.root_btn_type" :value="item.value">
                                    {{ item.title }}
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Фотография</label>
                            <image-field
                                v-model="form.avatar"
                                :src="selectedWidget.view_settings.avatar_url"
                                :border-color="form.avatar_border_color"
                            />
                        </div>
                        <div>
                            <label class="form-label">Цвет рамки</label>
                            <hex-color-picker v-model="form.avatar_border_color"/>
                        </div>
                    </div>
                    <div class="mt-4">
                        <toggle-switch
                            v-if="canDisableCopyright"
                            :disabled="!canDisableCopyright"
                            v-model:checked="form.disable_copyright"
                        >
                            Скрыть логотип DailyGrow
                        </toggle-switch>
                        <template  v-else>
                            <toggle-switch disabled>Скрыть логотип DailyGrow</toggle-switch>
                            <p class="text-xs text-gray-500">
                                Скрытие логотипа доступно на платном тарифе,
                                <nav-link class="link" :href="route('user.billing.new-payment.show')">подробнее</nav-link>.
                            </p>
                        </template>
                    </div>
                </div>

                <div class="card px-4 text-sm">
                    <div class="bg-gray-500/10 z-10 absolute h-full w-full top-0 left-0"
                         v-if="!form.welcome_enabled"
                    ></div>

                    <div class="flex items-center">
                        <h3 class="text-lg flex-shrink">Приветственное сообщение</h3>
                        <info-icon
                            v-if="false"
                            tooltip="*Описание приветственного сообщения*"
                            class="z-20 px-2 text-gray-400"
                        />
                        <div class="flex-grow"></div>
                        <toggle-switch v-model:checked="form.welcome_enabled" class="z-20 mr-2"/>
                    </div>

                    <div class="mt-3 space-y-4">
                        <div>
                            <label class="form-label">Заголовок всплывающего окна</label>
                            <input
                                class="form-control-sm w-full"
                                type="text"
                                v-model="form.welcome_message"
                                :required="form.welcome_enabled"
                            />
                        </div>
                        <div>
                            <label class="form-label">Время активации сообщения, сек</label>
                            <input
                                class="form-control-sm w-24"
                                type="number"
                                v-model="form.welcome_delay"
                                min="0"
                                :required="form.welcome_enabled"
                            />
                        </div>
                    </div>
                </div>

                <div class="card px-4 text-sm relative">
                    <div class="bg-gray-500/10 z-10 absolute h-full w-full top-0 left-0"
                         v-if="!form.popup_enabled"
                    ></div>

                    <div class="flex items-center">
                        <h3 class="text-lg flex-shrink">Всплывающее окно</h3>
                        <info-icon
                            v-if="false"
                            tooltip="*Описание всплывающего окна*"
                            class="z-20 px-2 text-gray-400"
                        />
                        <div class="flex-grow"></div>
                        <toggle-switch v-model:checked="form.popup_enabled" class="z-20 mr-2"/>
                    </div>

                    <div class="mt-3 space-y-4">
                        <div>
                            <label class="form-label">Имя менеджера</label>
                            <input
                                class="form-control-sm w-full"
                                type="text"
                                v-model="form.popup_title"
                                :required="form.popup_enabled"
                            />
                        </div>
                        <div>
                            <label class="form-label">Сообщение</label>
                            <input
                                class="form-control-sm w-full"
                                type="text"
                                v-model="form.popup_message"
                                :required="form.popup_enabled"
                            />
                        </div>
                        <div>
                            <label class="form-label">Телефон</label>
                            <input
                                class="form-control-sm w-full"
                                type="text"
                                v-model="form.popup_phone"
                            />
<!--                            <p class="text-xs p-1 text-gray-500">Должен начинаться с +7</p>-->
                        </div>
                    </div>
                </div>

                <validation-errors/>

                <div class="py-2">
                    <button type="submit" class="btn btn-md btn-primary">Сохранить</button>
                </div>
            </form>
            <div class="flex flex-row items-start justify-center md:py-8 py-2">
                <widget-preview :widget="{...selectedWidget, view_settings: form.data()}"/>
            </div>
        </div>
    </index>
</template>

<script setup>
import Index from "@/Pages/SocialWidget/Index.vue";
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import ImageField from "@/Pages/SocialWidget/Components/ImageField.vue";
import HexColorPicker from "@/Pages/SocialWidget/Components/HexColorPicker.vue";
import {watch} from "vue";
import InfoIcon from "@/Pages/Reviews/Components/InfoIcon.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import WidgetPreview from "@/Pages/SocialWidget/Widget/WidgetPreview.vue";
import NavLink from "@/Components/NavLink.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    widgets: {
        type: Array,
        required: true,
    },
    selectedWidget: {
        type: Object,
        required: false,
        default: null,
    },
    canDisableCopyright: {
        type: Boolean,
        required: false,
        default: true,
    },
});

const ROOT_BTN_TYPES = [
    {
        title: 'Иконки',
        value: 'static-icon',
    },
    {
        title: 'Анимация',
        value: 'animated-icon',
    },
    {
        title: 'Фотография',
        value: 'photo',
    },
];

const form = useForm({
    ...props.selectedWidget.view_settings,

    avatar: null,

    // Файлы только через post грузятся
    _method: 'put',
});
watch(() => props.selectedWidget, () => form.defaults({...form.defaults(), ...props.selectedWidget}).reset());

function onSubmit() {
    form.post(route('social-widget.private.settings.view.save', [props.project, props.selectedWidget]));
}
</script>
