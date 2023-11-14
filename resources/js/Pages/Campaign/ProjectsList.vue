<template>
    <page-title title="Список проектов" />

    <div class="mt-6 flex md:flex-row flex-col justify-start items-start">
        <period-controls :current-period-str="dateRangeStr"
                         :dont-send-form="true"
                         @change="onChangeDateRange"
                         name="dateRange"
        />
        <div class="flex-grow md:block hidden"></div>
        <nav-link :href="route('campaign.create')" class="btn btn-md btn-outline-base flex-shrink-0 md:mt-0 mt-2 md-w-auto-full">Создать проект</nav-link>
    </div>

    <div class="mt-6 flex flew-row justify-start items-center">
        <text-input v-model="searchString" class="max-w-xs py-2 px-4 inline" placeholder="ID или название проекта"/>
        <img alt="compare" src="/icons/colum.jpeg" class="cursor-pointer inline ml-2 w-6" @click="switchComparing"/>
    </div>

    <tree-table
        :projects="projects"
        :date-range="dateRangeProxy"
        :comparing-date-range="comparingDateRangeProxy"
        :comparing="isInComparing"
        class="w-full mt-6"
        :hidden-projects="hiddenProjects"
    />
</template>

<script setup>
import TreeTable from "@/Pages/Campaign/Components/ProjectsList/TreeTable.vue";
import PeriodControls from "@/Components/Forms/PeriodControls.vue";
import {computed, onBeforeMount, ref} from "vue";
import {dateRangeFormat, dateRangeToStr, getPreviousDateRange} from "@/dateRange.js";
import TextInput from "@/Components/Forms/TextInput.vue";
import {isEmpty, parseParamsFromUrl} from "@/utils.js";
import {usePage} from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import PageTitle from "@/Components/PageTitle.vue";

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
    dateRange: {
        type: [String, Array, Object],
        required: true,
    },
    comparingDateRange: {
        type: [String, Array, Object],
        required: true,
    },
});

const pageUrl = usePage().url;

onBeforeMount(() => {
    let params = parseParamsFromUrl(pageUrl);

    if (!isEmpty(params?.dateRange)) {
        dateRangeProxy.value = params.dateRange;
    }
    if (!isEmpty(params?.comparing)) {
        isInComparing.value = Boolean(params.comparing);
    }
});

const searchString = ref('');
const hiddenProjects = computed(() => {
    let search = searchString.value.toLowerCase();

    return props.projects
        .filter((project) => !(project.name + project.id).toLowerCase().includes(search))
        .map((project) => project.id);
});

const isInComparing = ref(false);

const dateRangeStr = computed(() => dateRangeFormat(dateRangeProxy.value, true, false));

const dateRange = ref();
const dateRangeProxy = computed({
    get: () => dateRangeToStr(dateRange.value ?? props.dateRange ?? '7days'),
    set: (val) => dateRange.value = val,
});

const comparingDateRangeProxy = computed(() => dateRangeToStr(getPreviousDateRange(dateRangeProxy.value)));

function onChangeDateRange(value) {
    dateRangeProxy.value = value;
    updateUrl();
}

function updateUrl() {
    let query = `?dateRange=${dateRangeToStr(dateRangeProxy.value)}`;
    if (isInComparing.value) {
        query += `&comparing=${isInComparing.value}`;
    }
    window.history.pushState("", "", query);
}

function switchComparing() {
    isInComparing.value = !isInComparing.value;
    updateUrl();
}

</script>

<script>
import layout from "@/Layouts/Authenticated.vue";

export default {layout};
</script>
