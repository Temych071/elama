<template>
    <private-layout :loyalties="props.loyalties" :loyalty="props.loyalty">
        <div class="form-field md:w-1/2 w-full mt-2">
            <label class="form-label">Ссылка на форму</label>
            <div class="flex flex-row gap-1">
                <text-input
                    class="form-control-sm "
                    :model-value="route('loyalty.public.login.show', loyalty.id)"
                    readonly
                />
                <a
                    class="btn btn-sm btn-outline-base"
                    :href="route('loyalty.public.login.show', loyalty.id)"
                    target="_blank"
                >Перейти</a>
            </div>
        </div>
        <tabs-layout
            :tabs="TABS"
            :route-params="[props.loyalty.project_id, props.loyalty.id]"
            class="mt-4"
        >
            <slot/>
        </tabs-layout>
    </private-layout>
</template>

<script setup>
import TabsLayout from "@/Pages/Loyalty/Layouts/TabsLayout.vue";
import PrivateLayout from "@/Pages/Loyalty/Layouts/PrivateLayout.vue";
import TextInput from "@/Components/Forms/TextInput.vue";

const props = defineProps({
    loyalties: {
        type: Array,
        required: true,
    },
    loyalty: {
        type: Object,
        required: false,
    },
});

const TABS = [
    {
        title: 'Аналитика',
        route: 'loyalty.private.loyalty.analytics.show',
    },
    {
        title: 'Онлайн анкета',
        route: 'loyalty.private.loyalty.form-settings.show',
    },
    {
        title: 'Дизайн карты',
        route: 'loyalty.private.loyalty.card-settings.show',
    },
    {
        title: 'Интеграция',
        route: 'loyalty.private.loyalty.integration-settings.show',
    },
];
</script>
