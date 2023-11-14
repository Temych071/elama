<template>
    <page-title title="Формы отзывов"/>

    <authenticated>
        <div class="px-6 py-4">
            <div>
                <h1 class="text-lg font-bold">Отзывы</h1>
                <p class="text-gray-500 text-sm">Форма обратной связи</p>
            </div>

            <div class="mt-4 flex items-center flex-wrap gap-2">
                <span v-for="form in reviewForms" :key="form.id">
                    <nav-link
                        class="btn btn-sm"
                        :class="`${form.id === reviewForm?.id ? 'btn-primary' : 'btn-base'}`"
                        :href="route('reviews.private.forms.show', {
                            campaign: project.id,
                            reviewForm: form.id,
                        })"
                    >{{ form.name }}
                    </nav-link>
                    <tippy content="Дублировать выбранную форму">
                        <img alt="copy"
                             src="/icons/reviews/form-copy.svg"
                             @click="onCopy(form.id)"
                             class="cursor-pointer inline"
                             v-if="form.id === reviewForm?.id"
                        />
                    </tippy>
                </span>
                <button
                    class="btn btn-sm btn-outline-primary mx-1"
                    @click="onCreateForm"
                >+
                </button>
            </div>

            <form class="mt-6 relative" v-if="isSelected" @submit.prevent="onSubmit">
                <loading-block v-if="form.processing" spinner-dark class="bg-gray-400/20">
                    Загрузка... <span v-if="form.progress">{{ form.progress }}%</span>
                </loading-block>

                <div class="flex flex-row items-center">
                    <input type="text"
                           v-model.trim="form.name"
                           class="font-bold rounded-x border-gray-200 text-md py-0.5 border-0 pl-0"
                    />
                    <div class="flex flex-row items-center" v-if="reviewForm">
                        <input type="text"
                               :value="route('reviews.public.show', reviewForm.slug)"
                               class="rounded-xl px-2 border-gray-200 text-sm py-0.5 ml-2 w-64 text-gray-800"
                               readonly
                               @focus="$event.target.select()"
                               ref="elFormLink"
                        />
                        <img
                            class="w-4 h-4 ml-2 cursor-pointer"
                            alt="copy"
                            src="/icons/copy.svg"
                            @click="copyLink"
                        >
                    </div>
                    <div class="flex-grow"></div>
                    <form-button
                        class="btn btn-md btn-outline-base ml-2"
                        :confirm="`Вы действительно хотите удалить форму '${reviewForm.name}'?`"
                        method="DELETE"
                        :action="route('reviews.private.forms.delete', {
                            campaign: project,
                            reviewForm: reviewForm,
                        })"
                    >Удалить форму
                    </form-button>
                </div>

                <div class="mt-4 max-w-[35rem] text-gray-500 grid md:grid-cols-2 grid-cols-1 gap-2">
                    <div>Тип формы</div>
                    <searchable-select
                        input-classes="select-sm"
                        class="text-sm"
                        :options="[
                            {title: 'Оценка и отзыв', value: 'default'},
                            {title: 'Только оценка', value: 'simple'},
                            {title: 'Обратная связь', value: 'feedback'},
                        ]"
                        v-model="form.type"
                    />

                    <div>Показывать отзывы</div>
                    <searchable-select
                        input-classes="select-sm"
                        class="text-sm"
                        :options="[
                            {title: 'Да', value: true},
                            {title: 'Нет', value: false},
                        ]"
                        v-model="form.page_settings.show_reviews_list"
                        :default-value="true"
                    />

                    <div class="flex flex-row items-center">
                        Оценка для публикации
                        <info-icon
                            class="w-3 ml-1"
                            :tooltip="`Оценки с рейтингом ${form.min_stars_for_publish} и выше будут автоматически перенаправляться на площадки.`"
                        />
                    </div>
                    <stars-input v-model="form.min_stars_for_publish"/>

                    <div class="flex flex-row items-center">
                        Уведомления о новых отзывах
                        <info-icon
                            class="w-3 ml-1"
                            :tooltip="`О всех оценках с рейтингом ${form.max_stars_for_notification} и ниже будет отправляться уведомление.`"
                        />
                    </div>
                    <stars-input v-model="form.max_stars_for_notification"/>

                    <div class="flex flex-row items-center">
                        Индекс виджета Яндекс.Карт
                    </div>
                    <input
                        v-model.number="form.widget_yamaps"
                        type="number"
                        class="form-control-sm"
                    />

                    <div class="flex flex-row items-center">
                        <span>Индекс виджета 2GIS</span>
                        <tippy content="Проверить доступ к ответам на отзывы в 2GIS">
                            <img
                                v-if="!reviewForm.widget_2gis_access"
                                alt="update 2gis access"
                                src="/icons/refresh.svg"
                                class="w-3 cursor-pointer mx-2"
                                @click="onUpdate2GisAccess"
                            />
                        </tippy>
                    </div>
                    <input
                        v-model="form.widget_2gis"
                        type="text"
                        class="form-control-sm"
                    />

                    <div class="flex flex-row items-center">
                        Индекс компании Яндекс.Бизнес
                        <info-icon
                            class="w-3 ml-1"
                            tooltip="Для привязки компании, доступ к ней должен быть
                            предоставлен сервисному аккаунту DailyGrow: dailygrow@yandex.ru.
                            Нажмите на иконку, чтобы скопировать имя аккаунта."
                            @click="navigator.clipboard.writeText('dailygrow@yandex.ru')"
                        />
                    </div>
                    <input
                        v-model.number="form.yandex_company_id"
                        type="number"
                        class="form-control-sm"
                    />

                    <template v-if="true">
                        <div class="h-2 col-span-full"></div>

                        <div class="form-field w-full">
                            <label class="label-sm">Telegram</label>
                            <input type="text"
                                   readonly
                                   class="form-control-sm  w-full"
                                   :value="codeTelegram ?? '-'"
                                   placeholder="Telegram"/>
                        </div>
                        <div class="form-field w-full">
                            <label class="label-sm">Email</label>
                            <input type="text"
                                   readonly
                                   class="form-control-sm  w-full"
                                   :value="emailAddress ?? '-'"
                                   placeholder="Email"/>
                        </div>

                        <div class="col-span-full flex flex-row justify-start">
                            <nav-link
                                :href="route('user.settings_notifications.show')"
                                class="btn btn-sm btn-primary"
                            >
                                Настройка уведомлений
                            </nav-link>
                        </div>
                    </template>
                </div>

                <div class="mt-4">
                    <button
                        class="btn btn-sm btn-primary"
                        @click="selectedTab = TAB_VIEW"
                        :disabled="selectedTab === TAB_VIEW"
                    >Оформление
                    </button>
                    <button
                        class="btn btn-sm btn-primary ml-2"
                        @click="selectedTab = TAB_CONTENT"
                        :disabled="selectedTab === TAB_CONTENT"
                    >Содержание
                    </button>
                </div>

                <div class="mt-2 py-2 grid gap-8 xl:grid-cols-2 grid-cols-1">
                    <div v-show="selectedTab === TAB_VIEW">
                        <div class="form-field ">
                            <label class="form-label">Логотип</label>
                            <image-field
                                v-model="form.logo"
                                v-model:deleted="form.logo_del"

                                :src="reviewForm.logo_url"
                                :accept="['image/png', 'image/jpeg', 'image/svg+xml']"
                            >
                                Предпочтительный тип файла: PNG (с прозрачным фоном).<br>
                                Рекомендуемый размер: не менее 390 x 200 px.
                            </image-field>
                        </div>

                        <div class="form-field mt-6">
                            <label class="form-label">Баннер</label>
                            <image-field
                                v-model="form.banner"
                                v-model:deleted="form.banner_del"

                                :src="reviewForm.banner_url"
                                :accept="['image/png', 'image/jpeg']"
                            >
                                Предпочтительный тип файла: JPG.<br>
                                Рекомендуемый размер: не менее 390 x 250 px.
                            </image-field>
                        </div>

                        <div class="form-field mt-2">
                            <input-text
                                v-model.trim="form.banner_link"
                                placeholder="Ссылка для баннера"
                                class="form-control"
                            />
                        </div>

                        <div class="form-field mt-6">
                            <label class="form-label">Баннер (страница благодарности)</label>
                            <image-field
                                v-model="form.thx_banner"
                                v-model:deleted="form.thx_banner_del"

                                :src="reviewForm.thx_banner_url"
                                :accept="['image/png', 'image/jpeg']"
                            >
                                <div class="grid grid-cols-2 gap-2">
                                    <input-text
                                        v-model.trim="form.thx_banner_link"
                                        class="form-control-sm col-span-2"
                                        placeholder="Ссылка для баннера"
                                    />
                                    <input
                                        v-model.trim="form.phrases.text_thx_button"
                                        class="form-control-sm"
                                        placeholder="Текст кнопки"
                                        type="text"
                                    />
                                    <input-text
                                        v-model.trim="form.thx_button_link"
                                        class="form-control-sm"
                                        placeholder="Ссылка для кнопки"
                                    />
                                </div>
                            </image-field>
                        </div>

                        <div class="grid md:grid-cols-2 grid-cols-1 gap-2 mr-4 mt-6">
                            <div class="grid md:grid-cols-1 grid-cols-2">
                                <label class="label-sm">Цвет фона</label>
                                <dropdown-color-picker
                                    v-model="form.page_settings.colors.background"
                                    default="#EFEFEF"
                                />
                            </div>
                            <div class="grid md:grid-cols-1 grid-cols-2">
                                <label class="label-sm">Цвет кнопок формы</label>
                                <dropdown-color-picker
                                    v-model="form.page_settings.colors.buttons"
                                    default="#FFCF00"
                                />
                            </div>
                            <div class="grid md:grid-cols-1 grid-cols-2">
                                <label class="label-sm">Цвет кнопок мессенджеров</label>
                                <dropdown-color-picker
                                    v-model="form.page_settings.colors.messengersButtons"
                                    default="#FFCF00"
                                />
                            </div>
                            <div class="grid md:grid-cols-1 grid-cols-2">
                                <label class="label-sm">Цвет текста кнопок</label>
                                <dropdown-color-picker
                                    v-model="form.page_settings.colors.buttonsText"
                                    default="#223354"
                                />
                            </div>
                        </div>
                    </div>
                    <div v-show="selectedTab === TAB_CONTENT">
                        <div>
                            <h3 class="font-bold">О компании</h3>
                            <input type="text"
                                   class="form-control mt-2"
                                   v-model="form.phrases.text_title"
                                   placeholder="Название компании"
                            />
                            <input type="text"
                                   class="form-control mt-2"
                                   v-model="form.phrases.text_address"
                                   placeholder="Адрес компании"
                            />
                        </div>

                        <div class="mt-4">
                            <h3 class="font-bold">Заголовок</h3>
                            <input type="text"
                                   class="form-control-sm block w-full mt-2"
                                   v-model="form.phrases.text_form_title"
                                   placeholder="Заголовок (страница оценки)"
                            />
                            <input type="text"
                                   class="form-control-sm block w-full mt-2"
                                   v-model="form.phrases.text_aggregators_title"
                                   placeholder="Заголовок (страница агрегаторов)"
                            />
                            <input type="text"
                                   class="form-control-sm block w-full mt-2"
                                   v-model="form.phrases.text_thx_title"
                                   placeholder="Заголовок (страница благодарности)"
                            />
                        </div>
                        <div class="mt-4">
                            <h3 class="font-bold">Описание</h3>
                            <input type="text"
                                   class="form-control-sm block w-full mt-2"
                                   v-model="form.phrases.text_form_desc"
                                   placeholder="Описание (страница оценки)"
                            />
                            <input type="text"
                                   class="form-control-sm block w-full mt-2"
                                   v-model="form.phrases.text_aggregators_desc"
                                   placeholder="Описание (страница агрегаторов)"
                            />
                            <input type="text"
                                   class="form-control-sm block w-full mt-2"
                                   v-model="form.phrases.text_thx_desc"
                                   placeholder="Описание (страница благодарности)"
                            />
                        </div>

                        <div class="mt-4">
                            <h3 class="font-bold">Контакты</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div>
                                    <div class="flex flex-row">
                                        <checkbox
                                            v-model:checked.number="form.page_settings.fields.name.show"
                                            :default="true"
                                        >Имя</checkbox>
                                        <checkbox
                                            v-model:checked.number="form.page_settings.fields.name.required"
                                            :default="true"
                                            class="ml-3 text-sm"
                                        >обязательно</checkbox>
                                    </div>
                                    <div class="flex flex-row">
                                        <checkbox
                                            v-model:checked.number="form.page_settings.fields.contact.show"
                                            :default="true"
                                        >Телефон / Email</checkbox>
                                        <checkbox
                                            v-model:checked.number="form.page_settings.fields.contact.required"
                                            class="ml-3 text-sm"
                                            :default="true"
                                        >обязательно</checkbox>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <input
                                        v-model.trim="form.phrases.text_before_contact_field"
                                        class="form-control-sm"
                                        placeholder="Хотите, чтобы с Вами связались?"
                                        type="text"
                                    />
                                    <input
                                        v-model.trim="form.phrases.ph_contact"
                                        class="form-control-sm"
                                        placeholder="Телефон / Email"
                                        type="text"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h3 class="font-bold">Мессенджеры</h3>
                            <div class="flex flex-row mt-2 items-center" v-for="(msngr, index) in form.messengers">
                                <input class="mr-2 form-control" v-model.trim="msngr.title" type="text"/>
                                <input class="mr-2 form-control" v-model.trim="msngr.link" type="text"/>
                                <button class="btn btn-md btn-danger-light"
                                        @click="form.messengers.splice(index, 1)"
                                        type="button"
                                >
                                    Удалить
                                </button>
                            </div>
                            <button class="btn btn-md btn-primary mt-2"
                                    @click="form.messengers.push({title: '', link: ''})"
                                    type="button"
                            >
                                Добавить
                            </button>
                        </div>

                        <div class="mt-4">
                            <h3 class="font-bold">Отзывы</h3>
                            <div class="flex flex-row mt-2 items-center"
                                 v-for="(ea, index) in form.external_aggregators"
                            >
                                <map-icon class="flex-shrink-0 mr-2" :url="ea.link"/>
                                <input class="mr-2 form-control" v-model.trim="ea.title" type="text"/>
                                <input class="mr-2 form-control" v-model.trim="ea.link" type="text"/>
                                <button class="btn btn-md btn-danger-light"
                                        @click="form.external_aggregators.splice(index, 1)"
                                        type="button"
                                >
                                    Удалить
                                </button>
                            </div>
                            <button class="btn btn-md btn-primary mt-2"
                                    @click="form.external_aggregators.push({title: '', link: ''})"
                                    type="button"
                            >
                                Добавить
                            </button>
                        </div>

                        <div class="mt-4">
                            <h3 class="font-bold">Политика конфиденциальности</h3>
                            <checkbox v-model:checked="form.page_settings.policy">Своя ссылка</checkbox>
                            <input type="text"
                                   :disabled="!form.page_settings.policy"
                                   v-model.trim="form.page_settings.policyLink"
                                   class="mt-2 form-control"
                                   placeholder="Введите ссылку на свою политику конфиденциальности"
                            />
                            <p class="text-gray-500">
                                <a :href="route('reviews.public.policy')" target="_blank" class="link">
                                    Политика конфиденциальности
                                </a>
                                по-умолчанию.
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex-shrink-0 flex justify-center">
                        <div>
                            <div class="flex flex-row flex-nowrap pb-4">
                                <div
                                    v-for="(ignored, state) in typeStates[form.type]"
                                    class="py-2 px-3 text-sm"
                                    :class="`${selectedPreviewState === state ? 'border-b-2 border-primary' : 'cursor-pointer'}`"
                                    @click="selectedPreviewState = state"
                                >
                                    {{ stateNames[state] }}
                                </div>
                            </div>

                            <div v-if="typeStates[form.type][selectedPreviewState]">
                                <review-form
                                    :review-form-settings="form"

                                    preview-mode
                                    disabled
                                    :can-submit="false"

                                    :forced-state="selectedPreviewState"
                                    :forced-form="typeStates[form.type][selectedPreviewState]"
                                    show-border
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <validation-errors/>

                <div class="mt-4">
                    <button type="submit" class="btn btn-md btn-primary mx-1">Сохранить</button>
                    <a class="btn btn-md btn-outline-base mx-1"
                       :href="route('reviews.public.show', reviewForm.slug)"
                       target="_blank"
                    >
                        Перейти
                        <!--                        Посмотреть-->
                    </a>
                </div>
            </form>
            <div v-else class="mt-6 text-sm text-gray-600">
                Выберите форму для редактирования или создайте новую.
            </div>
        </div>
    </authenticated>
</template>

<script setup>
import {computed, ref, watch} from "vue";
import {isEmpty2} from "@/utils.js";
import {useForm, router} from "@inertiajs/vue3";
import Authenticated from "@/Layouts/Authenticated.vue";
import NavLink from "@/Components/NavLink.vue";
import StarsInput from "@/Pages/Reviews/Components/StarsInput.vue";
import FormButton from "@/Components/Forms/FormButton.vue";
import DropdownColorPicker from "@/Pages/Reviews/Components/DropdownColorPicker.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import Checkbox from "@/Components/Checkbox.vue";
import MapIcon from "@/Pages/Reviews/Components/MapIcon.vue";
import ImageField from "@/Pages/Reviews/Components/ImageField.vue";
import Input from "@/Components/Input.vue";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import InfoIcon from "@/Pages/Reviews/Components/InfoIcon.vue";
import Tippy from "@/Components/Tippy.vue";
import PageTitle from "@/Components/PageTitle.vue";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";
import ReviewForm from "@/Pages/Reviews/Public/Components/ReviewForm.vue";
import InputText from "@/Pages/Reviews/Components/InputText.vue";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    reviewForm: {
        type: [Object, null],
        required: false,
        default: null,
    },
    reviewForms: {
        type: Array,
        required: false,
        default: [],
    },

    codeTelegram: {
        type: String,
        required: false,
        default: null,
    },
    emailAddress: {
        type: String,
        required: false,
        default: null,
    },
});

const elFormPreview = ref(null);

const TAB_VIEW = 'view';
const TAB_CONTENT = 'content';

const selectedTab = ref(TAB_VIEW);

const isSelected = computed(() => !isEmpty2(props.reviewForm?.id));

const typeStates = {
    'default': {
        // 'stars': null,
        'review': {stars: 3},
        // 'messengers': {stars: 1},
        'aggregators': {stars: 5},
        'thx': {stars: 1},
    },
    'simple': {
        'stars': {stars: 5},
        'review': {stars: 1},
        'aggregators': {stars: 5},
        'thx': {stars: 1},
    },
    'feedback': {
        // 'stars': null,
        'review': {stars: 3},
        // 'messengers': {stars: 1},
        'thx': {stars: 3},
    },
};

const selectedPreviewState = ref('review');

const stateNames = {
    'stars': 'Оценка',
    'review': 'Отзыв',
    'messengers': 'Мессенджеры',
    'aggregators': 'Агрегаторы',
    'thx': 'Благодарность',
};

let form = useForm({
    widget_yamaps: null,
    widget_2gis: null,
    ...props.reviewForm,
    logo: null,
    logo_del: false,

    banner: null,
    banner_del: false,

    thx_banner: null,
    thx_banner_del: false,

    // Файлы только через post грузятся
    _method: 'put',
});
watch(() => props.reviewForm, () => form.defaults({...form.defaults(), ...props.reviewForm}).reset())

function onUpdate2GisAccess() {
    router.post(route('reviews.private.forms.update-2gis-access', {
        campaign: props.reviewForm.project_id,
        reviewForm: props.reviewForm.id,
    }), {}, {
        preserveScroll: true,
        only: ['reviewForm'],
    });
}

function onCreateForm() {
    let formName = prompt('Введите название новой формы:');
    if (isEmpty2(formName)) {
        alert('Вы не ввели название формы.');
        return;
    }

    useForm({name: formName}).post(route('reviews.private.forms.init', props.project));
}

function onCopy(reviewFormId) {
    router.post(route('reviews.private.forms.copy', [props.project, reviewFormId]));
}

function onSubmit() {
    form.phrases = {...form.phrases}; // Хз в какой момент оно становится реактивным...

    form.post(route('reviews.private.forms.update', [props.project, props.reviewForm]), {
        // preserveState: true,
        // preserveScroll: true,
    });
}

function previewReset() {
    elFormPreview.value.reset();
}

const elFormLink = ref(null);

function copyLink() {
    navigator.clipboard.writeText(elFormLink.value.value);
    elFormLink.value.select();
}

</script>
