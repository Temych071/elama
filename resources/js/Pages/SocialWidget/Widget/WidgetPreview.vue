<template>
    <teleport to="head">
        <link rel="stylesheet" href="/social-widget/social-widget.v6.css">
    </teleport>
    <div class="dg__social-widget" style="position:relative;">
        <div class="flex w-full flex-row items-center relative" :class="`${widget.view_settings.position === 'left' ? 'justify-start' : 'justify-end'}`">
            <span class="relative">
                <div
                    v-if="widget.view_settings.welcome_enabled"
                    class="dg__social-widget__welcome"
                    :data-widget-pos="widget.view_settings.position"
                >
                    <div class="dg__social-widget__welcome__close"></div>
                    <div class="dg__social-widget__welcome__text">{{ widget.view_settings.welcome_message }}</div>
                </div>
                <div
                    class="dg__social-widget__root-button"
                    :data-type="rootBtnDataType"
                    :data-animated="rootBtnDataAnimated"
                    :data-photo="rootBtnDataPhoto"
                    :style="rootBtnStyles"
                />
            </span>
        </div>

        <hr style="margin-bottom: 64px; margin-top: 16px;"/>

        <div
            v-if="isPopup"
            class="dg__social-widget__popup"
        >
            <button class="dg__social-widget__popup__close">
                <svg width="10pt" height="12pt" viewBox="100 30 500 500" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" stroke="#000">
                        <path transform="matrix(23.333 0 0 23.333 70 0)" d="m21.5 12c0 5.2467-4.2534 9.5001-9.5001 9.5001s-9.5001-4.2534-9.5001-9.5001 4.2534-9.5001 9.5001-9.5001 9.5001 4.2534 9.5001 9.5001"/>
                        <path transform="matrix(23.333 0 0 23.333 70 0)" d="m8.0001 8.0001 7.9999 7.9999" stroke-linecap="square"/>
                        <path transform="matrix(23.333 0 0 23.333 70 0)" d="m8.0001 16 7.9999-7.9999" stroke-linecap="square"/>
                    </g>
                </svg>
            </button>

            <img
                v-if="widget.view_settings.avatar_url"
                :src="widget.view_settings.avatar_url"
                alt="avatar"
                class="dg__social-widget__popup__avatar"
                :style="{'border-color': widget.view_settings.avatar_border_color}"
            >

            <p class="dg__social-widget__popup__title">{{ widget.view_settings.popup_title }}</p>
            <p class="dg__social-widget__popup__message">{{ widget.view_settings.popup_message }}</p>

            <hr/>

            <div class="dg__social-widget__buttons">
                <widget-preview-button type="tg" v-if="widget.messengers_settings.tg_enabled"/>
                <widget-preview-button type="wa" v-if="widget.messengers_settings.wa_enabled"/>
            </div>

            <p class="dg__social-widget__popup__phone">{{ widget.view_settings.popup_phone }}</p>
            <p class="dg__social-widget__popup__copyright" v-if="!widget.view_settings.disable_copyright">Сделано в DailyGrow</p>
        </div>
        <div
            v-else
        >
            <div class="dg__social-widget__simple-popup">
                <span class="dg__social-widget__buttons">
                    <widget-preview-button type="tg" v-if="widget.messengers_settings.tg_enabled"/>
                    <widget-preview-button type="wa" v-if="widget.messengers_settings.wa_enabled"/>
                </span>
                <span class="dg__social-widget__v-line" style="color: lightgray"></span>
                <span class="dg__social-widget__simple-popup__close">x</span>
            </div>
        </div>
    </div>

    <div>

    </div>
</template>

<script setup>
import {computed} from "vue";
import WidgetPreviewButton from "@/Pages/SocialWidget/Widget/Components/WidgetPreviewButton.vue";

const props = defineProps({
    widget: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close']);

const isPopup = computed(() => props.widget.view_settings.popup_enabled);

const rootBtnDataType = computed(() => {
    if (
        props.widget.messengers_settings.tg_enabled
        && props.widget.messengers_settings.wa_enabled
    ) {
        return 'tg-wa';
    } else if (props.widget.messengers_settings.tg_enabled) {
        return 'tg';
    } else {
        return 'wa';
    }
});

const rootBtnDataAnimated = computed(() => {
    switch (props.widget.view_settings.root_btn_type ?? 'animated-icon') {
        case 'animated-icon':
            return 'true';
        case 'static-icon':
            return 'false';
        case 'photo':
            return null;
    }
});

const rootBtnDataPhoto = computed(() => {
    return props.widget.view_settings.root_btn_type === 'photo'
        ? ''
        : null;
});

const rootBtnStyles = computed(() => {
    let res = {
        height: `${props.widget.view_settings.avatar_size}px`,
        width: `${props.widget.view_settings.avatar_size}px`,
    };

    if (props.widget.view_settings.root_btn_type === 'photo') {
        res['background-image'] = `url(${props.widget.view_settings.avatar_url})`;
        res['border-color'] = props.widget.view_settings.avatar_border_color;
    }

    return res;
});

</script>
