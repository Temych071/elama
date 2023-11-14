<template>
    <div class="campaign-menu-item-container" v-if="isVisible">
        <a
            class="campaign-menu-item"
            :class="{
                active: isActive,
                collapsed: isCollapsed,
                parent: nested,
                nested: isNested,
            }"
            @click.stop="onClick"
            :href="href"
        >
            <sidebar-menu-item-icon
                v-if="!isNested"
                :icon="icon"
            />
            <span class="campaign-menu-item__label">{{ title }}</span>
        </a>

        <div class="pl-2 campaign-menu__nested"
             :class="{
                collapsed: isCollapsed,
             }"
             v-show="nested && isDeployed"
             @click.prevent.stop="isDeployed = true"
        >
            <!--suppress RequiredAttributes -->
            <sidebar-menu-item
                v-for="item in nested"

                v-bind="item"
                :is-collapsed="isCollapsed"
                :project-id="projectId"

                @active="onActiveNested"
                :is-nested="true"
            />
        </div>
    </div>
</template>

<script setup>
import SidebarMenuItemIcon from "@/Components/NavMenu/SidebarMenuItemIcon.vue";
import {computed, onMounted, onUnmounted, ref, watch} from "vue";
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    href: {
        type: String,
        required: false,
        default: null,
    },
    route: {
        type: String,
        required: false,
        default: null,
    },
    checkRoute: {
        type: String,
        required: false,
        default: null,
    },
    icon: {
        type: String,
        required: false,
        default: null,
    },
    forCampaign: {
        type: Boolean,
        required: false,
        default: false,
    },
    nested: {
        type: Array,
        required: false,
        default: null,
    },
    visible: {
        type: Boolean,
        required: false,
        default: true,
    },
    forAdmins: {
        type: Boolean,
        required: false,
        default: false,
    },

    projectId: {
        type: Number,
        required: false,
        default: null,
    },

    isCollapsed: {
        type: Boolean,
        required: false,
        default: false,
    },

    isNested: {
        type: Boolean,
        required: false,
        default: false,
    },

    target: {
        type: String,
        required: false,
        default: '_self',
    },
});

const emit = defineEmits(['active']);

const isNestedActive = ref(false);
const isDeployed = ref(false);

const isVisible = computed(() => {
    if (!props.visible) {
        return false;
    }

    if (props.forAdmins && usePage().props.auth?.user?.role !== 'admin') {
        return false;
    }

    return true;
});

const isActive = computed(() => {
    // refresh this value after update page url
    usePage().url;

    if (props.href) {
        return false;
    }

    if (props.nested) {
        return isNestedActive.value;
    }

    let checkRoute = props.checkRoute ?? props.route;
    return route().current(checkRoute);
});

watch(isActive, () => {
    emit('active', isActive.value);
});

const href = computed(() => {
    if (props.nested) {
        return null;
    }

    if (props.href) {
        return props.href;
    }

    if (props.forCampaign) {
        return route(props.route, {campaign: props.projectId});
    }

    return route(props.route);
});

const onMouseDown = () => {
    if (props.isCollapsed) {
        isDeployed.value = false;
    }
};

onMounted(() => {
    // isDeployed.value = isNestedActive.value;
    emit('active', isActive.value);
    document.addEventListener('click', onMouseDown);
});

onUnmounted(() => {
    document.removeEventListener('click', onMouseDown);
});

watch(isNestedActive, () => {
    if (!props.isCollapsed) {
        isDeployed.value = isNestedActive.value;
    }
});

function onClick(e) {
    if (props.nested) {
        isDeployed.value = !isDeployed.value;
    } else if (props.href) {
        window.open(props.href, props.target);
    } else {
        router.visit(href.value);
    }
    e.preventDefault();
}

function onActiveNested(active) {
    if (isNestedActive.value) {
        return;
    }

    isNestedActive.value = active;
}

</script>

