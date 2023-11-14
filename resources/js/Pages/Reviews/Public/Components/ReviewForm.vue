<template>
    <div
        class="bg-[#ffffff] md:min-w-sm md:max-w-sm max-w-auto min-w-auto w-full md:rounded-md p-0 overflow-hidden relative"
        :class="{border: showBorder}"
    >
        <div
            v-if="disabled"
            class="absolute top-0 left-0 w-full h-full z-[9999] cursor-not-allowed"
        ></div>

        <template v-if="page === PAGE_REVIEW">
            <form-header
                :average-rating="averageRating"
                :get-phrase="getPhrase"
                :review-form-settings="reviewFormSettings"
            />

            <div class="px-6 py-4">
                <loading-block v-if="form.processing" spinner-dark class="bg-gray-200/40"/>

                <h2 class="text-xl font-bold text-center px-12 mb-4">
                    {{ getPhrase('text_form_title') }}
                </h2>
                <p class="text-sm mb-4 text-center">
                    {{ getPhrase('text_form_desc') }}
                </p>

                <stars-input
                    v-model="form.stars"
                    star-class="w-12"
                    class="justify-center"
                />

                <div
                    v-if="state === STATE_MESSENGERS"
                    class="grid grid-cols-1 gap-y-2 py-4 px-8 bg-sidebar mt-4 bg-gray-500/5"
                >
                    <p class="text-sm text-center text-gray-500">
                        {{ getPhrase('text_before_messengers') }}
                    </p>

                    <a class="btn btn-md"
                       v-for="messenger in reviewFormSettings.messengers"
                       :href="messenger.link"
                       target="_blank"
                       :style="{'background-color': btnMsgColor, 'color': btnTextColor}"
                    >{{ messenger.title }}</a>
                </div>

                <form @submit.prevent="onSubmit" class="mt-4" v-if="form.stars">
                    <template
                        v-if="showForm"
                    >
                        <input-text-area
                            v-model.trim="form.comment"
                            autocomplete="off"
                            class="form-control mt-2"
                            :placeholder="getPhrase('ph_comment')"
                            required
                            autosize
                            :min-size="62"
                        />

                        <input v-if="field('name').show"
                               v-model.trim="form.name"
                               type="text"
                               autocomplete="name"
                               class="form-control mt-2"
                               :placeholder="getPhrase('ph_name')"
                               :required="field('name').required"
                        />

                        <div class="form-field pt-2" v-if="field('contact').show">
                            <label class="text-xs text-gray-400 font-bold font-normal">
                                {{ getPhrase('text_before_contact_field') }}
                            </label>
                            <input class="form-control"
                                   v-model="form.contact"
                                   autocomplete="tel"
                                   type="text"
                                   :placeholder="getPhrase('ph_contact')"
                                   :required="field('contact').required"
                            >
                        </div>
                    </template>

                    <div class="form-field mt-4">
                        <button
                            class="btn w-full text-base"
                            type="submit"
                            :style="{'background-color': btnColor, 'color': btnTextColor, 'font-size': '1rem'}"
                        >
                            {{ getPhrase('btn_send_review') }}
                        </button>
                    </div>
                    <checkbox
                        class="text-xs"
                        v-model:checked="form.policy"
                        v-if="showForm"
                    >
                        C <a :href="policyLink" target="_blank" class="link">Политикой конфиденциальности</a> ознакомлен
                        и согласен.
                    </checkbox>
                </form>
            </div>
        </template>

        <template v-else-if="page === PAGE_AGGREGATORS">
            <form-header
                :average-rating="averageRating"
                :get-phrase="getPhrase"
                :review-form-settings="reviewFormSettings"
                v-if="!reviewFormSettings.banner_url"
            />
            <a v-else target="_blank" :href="reviewFormSettings.banner_link">
                <img class="object-cover w-full max-h-64" alt="banner"
                     :src="reviewFormSettings.banner_url"/>
            </a>

            <div class="px-6 py-4">
                <p class="text-xl font-bold text-center px-12 mb-4">
                    {{ getPhrase('text_aggregators_title') }}
                </p>
                <p class="text-sm mb-4 text-center">
                    {{ getPhrase('text_aggregators_desc') }}
                </p>

                <div class="w-full grid grid-cols-1 gap-y-2" v-if="reviewFormSettings.external_aggregators">
                    <a class="flex flex-row items-center text-black bg-black/5 border-0 text-left py-4 px-6 text-left text-sm font-bold rounded-lg"
                       v-for="aggregator in reviewFormSettings.external_aggregators"
                       :href="aggregator.link"
                       target="_blank"
                       @click="onAggregatorClick"
                    >
                        <map-icon :url="aggregator.link" class="mr-4 flex-shrink-0"/>
                        <span>Оставить отзыв на {{ aggregator.title }}</span>
                    </a>
                </div>
            </div>
        </template>

        <template v-if="page === PAGE_THX">
            <form-header
                :average-rating="averageRating"
                :get-phrase="getPhrase"
                :review-form-settings="reviewFormSettings"
                v-if="!thxBannerUrl"
            />
            <a v-else target="_blank" :href="thxBannerLink">
                <img class="object-cover w-full max-h-64"
                     alt="banner"
                     :src="thxBannerUrl"
                />
            </a>

            <div class="px-6 py-4 flex flex-col justify-center items-center">
                <h2 class="text-xl font-bold text-center px-12 mb-4">
                    {{ getPhrase('text_thx_title') }}
                </h2>
                <p class="text-sm mb-4 text-center">
                    {{ getPhrase('text_thx_desc') }}
                </p>

                <a
                    v-if="reviewFormSettings.thx_button_link"
                    class="btn btn w-full my-2" :style="{'background-color': btnMsgColor}"
                    :href="reviewFormSettings.thx_button_link"
                    target="_blank"
                >
                    {{ getPhrase('text_thx_button') }}
                </a>
            </div>
        </template>

        <div class="px-6 pb-4">
            <a class="text-gray-400 text-[10px] block underline text-right mt-8"
               target="_blank"
               href="https://dailygrow.ru/"
            >
                Сервис предоставлен DailyGrow
            </a>
        </div>
    </div>

    <popup v-model:shown="popupCopiedShown">
        <div class="text-center">Отзыв скопирован в буфер обмена.</div>
    </popup>
</template>

<script setup>
import StarsInput from "@/Pages/Reviews/Components/StarsInput.vue";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import Checkbox from "@/Components/Checkbox.vue";
import MapIcon from "@/Pages/Reviews/Components/MapIcon.vue";
import FormHeader from "@/Pages/Reviews/Public/Components/FormHeader.vue";
import {computed, onMounted, ref, watch} from "vue";
import {isEmpty2} from "@/utils.js";
import {useForm} from "@inertiajs/vue3";
import Popup from "@/Pages/Reviews/Components/Popup.vue";
import InputTextArea from "@/Pages/Reviews/Components/InputTextArea.vue";

const props = defineProps({
    reviewFormSettings: {
        type: Object,
        required: true,
    },
    averageRating: {
        type: Number,
        required: false,
        default: 4.5,
    },
    // getPhrase: {
    //     type: Function,
    //     required: true,
    // },
    canSubmit: {
        type: Boolean,
        required: false,
        default: true,
    },
    disabled: {
        type: Boolean,
        required: false,
        default: false,
    },
    previewMode: {
        type: Boolean,
        required: false,
        default: false,
    },

    forcedState: {
        type: String,
        required: false,
        default: null,
    },
    forcedForm: {
        type: Object,
        required: false,
        default: null,
    },

    showBorder: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const emit = defineEmits(['reviewSent']);

const DEFAULT_FIELDS = {
    name: {
        show: true,
        required: true,
    },
    contact: {
        show: false,
        required: false,
    },
};

defineExpose({
    reset,
});

onMounted(() => {
    applyForcedForm();
});

watch(() => props.forcedForm, () => {
    applyForcedForm();
});

function applyForcedForm() {
    if (props.forcedForm) {
        for (let key in props.forcedForm) {
            form[key] = props.forcedForm[key];
        }
    }
}

const policyLink = computed(() =>
    props.reviewFormSettings.page_settings.policy
        ? props.reviewFormSettings.page_settings.policyLink
        : route('reviews.public.policy')
);

const isAggregatorClicked = ref(false);
function onAggregatorClick() {
    isAggregatorClicked.value = true;
}

const btnColor = computed(() => props.reviewFormSettings?.page_settings?.colors?.buttons ?? '#FFCF00');
const btnMsgColor = computed(() => props.reviewFormSettings?.page_settings?.colors?.messengersButtons ?? '#FFCF00');
const btnTextColor = computed(() => props.reviewFormSettings?.page_settings?.colors?.buttonsText ?? '#252733');
const thxBannerUrl = computed(() => props.reviewFormSettings.thx_banner_url ?? props.reviewFormSettings.banner_url ?? null);
const thxBannerLink = computed(() => props.reviewFormSettings.thx_banner_link ?? props.reviewFormSettings.banner_link ?? null);

const STATE_STARS = 0;
const STATE_REVIEW = 1;
const STATE_MESSENGERS = 2;
const STATE_AGGREGATORS = 3;
const STATE_THX = 4;

const state = computed(() => {
    if (!isEmpty2(props.forcedState)) {
        const res = {
            'stars': STATE_STARS,
            'review': STATE_REVIEW,
            'messengers': STATE_MESSENGERS,
            'aggregators': STATE_AGGREGATORS,
            'thx': STATE_THX,
        }[props.forcedState] ?? null;

        if (!isEmpty2(res)) {
            return res;
        }
    }

    return {
        'default': stateDefault,
        'simple': stateSimple,
        'feedback': stateFeedback,
    }[props.reviewFormSettings.type ?? 'default']();
});

function stateDefault() {
    if (
        !reviewSent.value
        && !form.stars
    ) {
        return STATE_STARS;
    } else if (
        !reviewSent.value
        && form.stars >= props.reviewFormSettings.min_stars_for_publish
    ) {
        return STATE_REVIEW;
    } else if (
        !reviewSent.value
        && form.stars < props.reviewFormSettings.min_stars_for_publish
    ) {
        return STATE_MESSENGERS;
    } else if (
        reviewSent.value
        && form.stars >= props.reviewFormSettings.min_stars_for_publish
        && !isAggregatorClicked.value
    ) {
        return STATE_AGGREGATORS;
    } else {
        return STATE_THX;
    }
}

function stateSimple() {
    if (
        !reviewSent.value
        && (
            !form.stars
            || form.stars >= props.reviewFormSettings.min_stars_for_publish
        )
    ) {
        return STATE_STARS;
    } else if (
        !reviewSent.value
        && form.stars < props.reviewFormSettings.min_stars_for_publish
    ) {
        return STATE_MESSENGERS;
    } else if (
        reviewSent.value
        && form.stars >= props.reviewFormSettings.min_stars_for_publish
        && !isAggregatorClicked.value
    ) {
        return STATE_AGGREGATORS;
    } else {
        return STATE_THX;
    }
}

function stateFeedback() {
    if (
        !reviewSent.value
        && !form.stars
    ) {
        return STATE_STARS;
    } else if (
        !reviewSent.value
        && form.stars >= props.reviewFormSettings.min_stars_for_publish
    ) {
        return STATE_REVIEW;
    } else if (
        !reviewSent.value
        && form.stars < props.reviewFormSettings.min_stars_for_publish
    ) {
        return STATE_MESSENGERS;
    } else if (reviewSent.value) {
        return STATE_THX;
    }
}

const showForm = computed(() => [
    STATE_REVIEW,
    STATE_MESSENGERS,
].includes(state.value));

const PAGE_REVIEW = 0;
const PAGE_AGGREGATORS = 1;
const PAGE_THX = 2;

const page = computed(() => {
    return {
        [STATE_STARS]: PAGE_REVIEW,
        [STATE_REVIEW]: PAGE_REVIEW,
        [STATE_MESSENGERS]: PAGE_REVIEW,
        [STATE_AGGREGATORS]: PAGE_AGGREGATORS,
        [STATE_THX]: PAGE_THX,
    }[state.value ?? STATE_STARS];
});

function field(field) {
    return (props.reviewFormSettings?.page_settings?.fields ?? DEFAULT_FIELDS)[field] ?? DEFAULT_FIELDS[field];
}

const form = useForm({
    stars: 0,
    name: '',
    comment: '',
    contact: '',
    policy: true,
});

const reviewSent = ref(false);

function onSubmit() {
    if (props.reviewFormSettings.type === 'simple' && form.stars >= props.reviewFormSettings.min_stars_for_publish) {
        form.reset('comment', 'name', 'contact');
    }

    if (form.stars >= props.reviewFormSettings.min_stars_for_publish && !isEmpty2(form.comment)) {
        navigator.clipboard.writeText(form.comment);
        showPopupCopied();
    }

    if (props.previewMode) {
        reviewSent.value = true;
        emit('reviewSent', form.data());
        return;
    }

    form.processing = true;
    axios.post('', form.data()).then(() => {
        reviewSent.value = true;
        emit('reviewSent', form.data());
        form.processing = false;
    });

    // form.post('', {
    //     onSuccess: () => {
    //         reviewSent.value = true;
    //         emit('reviewSent', form.data());
    //     }
    // });
}

function reset() {
    form.reset();
    reviewSent.value = false;
}

const DEFAULT_PHRASES = {
    text_title: '',
    text_address: 'Москва, Пресненская набережная, 10',
    text_thx_for_choose_us: 'Спасибо, что выбрали нас!',
    text_in_green_card: 'Нам очень важно знать, что Вас устраивает, а что не устраивает в работе с нами!',
    text_before_stars: 'Оставьте отзыв',
    // text_thx_for_review: 'Спасибо за Вашу оценку, %username%!',
    text_before_contact_field: '',
    text_before_messengers: 'Произошла неприятная ситуация? Готовы вам помочь!',

    btn_send_review: 'Отправить',

    text_form_title: 'Оставьте отзыв о нашей работе',
    text_form_desc: '',

    text_aggregators_title: 'Спасибо за вашу высокую оценку',
    text_aggregators_desc: 'Будем благодарны, если вы оставьте отзыв на одной из платформ ниже',

    text_thx_title: 'Спасибо, Ваш отзыв отправлен',
    text_thx_desc: '',

    ph_name: 'Имя',
    ph_comment: 'Текст отзыва',
    ph_contact: 'Телефон / Email',
};

const phrases = computed(() => props.reviewFormSettings?.phrases ?? DEFAULT_PHRASES);

function getPhrase(key) {
    if (!isEmpty2(phrases.value[key])) {
        return phrases.value[key];
    }

    return DEFAULT_PHRASES[key] ?? '';
}

const popupCopiedShown = ref(false);

function showPopupCopied() {
    popupCopiedShown.value = true;
    setTimeout(() => {
        popupCopiedShown.value = false;
    }, 5500);
}
</script>
