<template>
    <page-title title="Виджеты"/>
    <authenticated>
        <p v-if="isEmpty2(widgets)" class="py-2">
            Вы ещё не создавали виджеты.
        </p>
        <div class="flex flex-row flex-wrap">
            <div class="flex flex-row flex-wrap flex-grow">
                <nav-link
                    v-for="widget in widgets"
                    :key="widget.id"
                    type="button"
                    class="btn btn-sm btn-primary mr-2 mb-2"
                    :class="{disabled: selectedWidget?.id === widget.id}"
                    :href="route('social-widget.private.stats', {
                        campaign: widget.project_id,
                        socialWidget : widget.id,
                    })"
                >{{ widget.name }}</nav-link>
                <button
                    type="button"
                    class="btn btn-sm btn-primary mr-2 mb-2 w-24"
                    @click="onCreateWidget"
                >+</button>
            </div>
            <button
                v-if="!isEmpty2(selectedWidget)"
                class="btn btn-md btn-outline-base"
                @click="onDeleteWidget"
            >Удалить виджет</button>
        </div>

        <div
            v-if="!isEmpty2(selectedWidget)"
            class="flex flex-row space-x-2 my-4"
        >
            <nav-link
                v-for="menuItem in WIDGET_MENU"
                :href="route(menuItem.route, {
                    campaign: selectedWidget.project_id,
                    socialWidget : selectedWidget.id,
                })"
                :class="{'border-b-2 border-primary': route().current(menuItem.route)}"
                class="py-2 px-4 text-sm"
            >{{ menuItem.title }}</nav-link>
        </div>

        <slot/>
    </authenticated>
</template>

<script setup>
import Authenticated from "@/Layouts/Authenticated.vue";
import NavLink from "@/Components/NavLink.vue";
import {isEmpty2} from "@/utils.js";
import {router} from "@inertiajs/vue3";
import PageTitle from "@/Components/PageTitle.vue";

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
});

const WIDGET_MENU = [
    {
        title: 'Статистика',
        route: 'social-widget.private.stats',
    },
    {
        title: 'Настройка каналов',
        route: 'social-widget.private.settings.channels',
    },
    {
        title: 'Дизайн',
        route: 'social-widget.private.settings.view',
    },
    {
        title: 'Интеграции',
        route: 'social-widget.private.settings.integrations',
    },
];

function onCreateWidget() {
    router.post(route('social-widget.private.create', props.project.id), {
        name: prompt('Введите название виджета', 'Новый виджет'),
    });
}

function onDeleteWidget() {
    if (!confirm(`Вы действительно хотите удалить виджет '${props.selectedWidget.name}'?`)) {
        return;
    }

    router.delete(route('social-widget.private.delete', [props.project.id, props.selectedWidget.id]));
}

</script>
