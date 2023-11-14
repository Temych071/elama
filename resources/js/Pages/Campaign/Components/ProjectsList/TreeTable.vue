<template>
    <div class="relative overflow-x-auto">
        <loading-block class="bg-white bg-opacity-50"
                       spinner-dark
                       v-if="inLoading"
        />

        <table class="projects-list min-w-full">
            <thead>
            <tr style="user-select: none">
                <th class="projects-list__th-item">
                    Название
                </th>
                <th colspan="1"
                    v-for="(options, key) in fields"
                    class="projects-list__th-item"
                >
                    <sort-select-button
                        :field="key"
                        v-model="sortProxy"
                    >{{ options.title }}
                    </sort-select-button>
                </th>
                <th class="projects-list__th-item"></th>
            </tr>
            </thead>
            <tbody>
            <template v-for="project in sortedProjects" :key="project.id">
                <tr v-show="!hiddenProjects.includes(project.id)">
                    <td class="flex flex-row justify-start items-center flex-nowrap whitespace-nowrap w-full">
                        <slider-deploy-button
                            v-model:is-deployed="projectSliderStates[project.id]"
                            class="mr-1 flex-shrink-0"
                            :class="{'opacity-0 cursor-default': isEmpty2(projectRootItems[project.id])}"
                        />
                        <a
                            :href="route('campaign.redirect', {campaign: project.id})"
                            class="font-bold cursor-pointer flex-grow"
                            target="_blank"
                        >
                            {{ project.name }}
                        </a>
                        <div class="flex-shrink-0 flex flex-row flex-nowrap">
                            <source-icon
                                v-for="source in project.sources"
                                :size="16"
                                :source-type="source.settings_type"
                                class="ml-2"
                            />
                        </div>
                    </td>
                    <td v-for="(ignored, key) in fields" class="projects-list__th-item">
                        <percents-for-sorted
                            :field="key"
                            :metrics="projectSummaryMetrics[project.id] ?? {}"
                            :comparing-metrics="projectComparingSummaryMetrics[project.id] ?? {}"
                            :comparing="comparing"
                            :fields="fields"
                        />
                    </td>
                    <td class="flex flew-row flex-nowrap items-center justify-center">
                        <nav-link :href="route('campaign.source', {campaign: project.id})" class="flex-shrink-0">
                            <img alt="settings" src="/icons/cog-small.svg" class="w-6 p-1">
                        </nav-link>
                        <nav-link :href="route('campaign.edit', {campaign: project.id})" class="flex-shrink-0">
                            <img alt="settings" src="/icons/edit.svg" class="w-6 p-1">
                        </nav-link>
                        <nav-link :href="route('campaign.delete', {campaign: project.id})" class="flex-shrink-0">
                            <img alt="settings" src="/icons/delete.svg" class="w-5 p-1">
                        </nav-link>
                    </td>
                </tr>
                <tree-table-items
                    :hidden="!(projectSliderStates[project.id] ?? false) || hiddenProjects.includes(project.id)"

                    :project="project"
                    :date-range="dateRange"
                    :comparing-date-range="comparingDateRange"

                    :sort-state="sort"
                    :fields="fields"
                    :comparing="comparing"

                    :use-external-data="true"
                    :items-data="projectRootItems[project.id] ?? []"
                    :comparing-items-data="projectComparingRootItems[project.id] ?? []"
                />
            </template>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import {computed, onMounted, ref, watch} from "vue";
import {isEmpty, r, isEmpty2, separateNumber, analyticsCalcSummary} from "@/utils";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import TreeTableItems from "@/Pages/Campaign/Components/ProjectsList/TreeTableItems.vue";
import SortSelectButton from "@/Pages/Campaign/Components/ProjectsList/SortSelectButton.vue";
import SourceIcon from "@/Pages/Sources/Components/SourceIcon.vue";
import SliderDeployButton from "@/Pages/Campaign/Checks/Components/SliderDeployButton.vue";
import PercentsForSorted from "@/Pages/Campaign/Components/ProjectsList/PercentsForSorted.vue";
import NavLink from "@/Components/NavLink.vue";

const props = defineProps({
    dateRange: {
        type: String,
        required: true,
    },
    projects: {
        type: Array,
        required: true,
    },

    comparingDateRange: {
        type: String,
        required: false,
        default: '7days',
    },
    comparing: {
        type: Boolean,
        required: false,
        default: false,
    },

    fieldsSettings: {
        type: [Object, Array, null],
        required: false,
        default: null,
    },

    hiddenProjects: {
        type: Array,
        required: false,
        default: [],
    },
});

const projectRootItems = ref({});
const projectComparingRootItems = ref({});

const projectSummaryMetrics = computed(() => {
    let res = {};

    for (let projectId in projectRootItems.value) {
        res[projectId] = analyticsCalcSummary(projectRootItems.value[projectId]);
    }

    return res;
});

const projectComparingSummaryMetrics = computed(() => {
    let res = {};

    for (let projectId in projectComparingRootItems.value) {
        res[projectId] = analyticsCalcSummary(projectComparingRootItems.value[projectId]);
    }

    return res;
});

onMounted(() => {
    loadData();
});

const projectIds = computed(() => props.projects.map((project) => project.id));

async function loadData() {
    if (props.comparing && isEmpty2(projectComparingRootItems.value)) {
        axios.get(route('campaign.analytics-data.projects', {
            project_ids: projectIds.value,
            dateRange: props.comparingDateRange,
            path: props.path,
        })).then(({data}) => {
            projectComparingRootItems.value = data;
        });
    }

    inLoading.value = true;

    if (isEmpty2(projectRootItems.value)) {
        projectRootItems.value = (await axios.get(route('campaign.analytics-data.projects', {
            project_ids: projectIds.value,
            dateRange: props.dateRange,
            path: props.path,
        }))).data;
    }

    inLoading.value = false;
}

function reloadData() {
    inLoading.value = true;

    projectRootItems.value = [];
    projectComparingRootItems.value = [];
    loadData();
}

watch(() => props.comparing, loadData);
watch(() => props.dateRange, reloadData);

const FIELDS_SORT_REVERSES = {
    cpl: true,
    cpc: true,
};

const sortedProjects = computed(() => {
    if (!props.projects) {
        return [];
    }

    let res = [...props.projects];

    if (isEmpty(sort.value)) {
        return res;
    }

    let dir = sort.value.direction;
    if (FIELDS_SORT_REVERSES[sort.value.field] ?? false) {
        dir = !dir;
    }

    const getVal = (project) => {
        const summary = projectSummaryMetrics.value[project.id] ?? {};
        return summary[sort.value.field] ?? 0;
    };

    return res.sort(
        dir
            ? (a, b) => getVal(a) - getVal(b)
            : (a, b) => getVal(b) - getVal(a)
    );
});

const projectSliderStates = ref({});

// expenses, requests, clicks, cpc, cr, cpl, purchases, income, drr
const HEADER_FIELDS = {
    expenses: {
        title: 'Расходы',
        callback: (value) => `${separateNumber(r(value, 0))} ₽`,
        reverse: true,
    },
    requests: {
        title: 'Заявки',
        callback: (value) => separateNumber(r(value, 0)),
    },
    clicks: {
        title: 'Клики',
        callback: (value) => separateNumber(r(value, 0)),
    },
    cpc: {
        title: 'CPC',
        callback: (value) => `${separateNumber(r(value, 0))} ₽`,
        reverse: true,
    },
    cr: {
        title: 'CR',
        callback: (value) => `${separateNumber(r(value, 2))} %`,
    },
    cpl: {
        title: 'CPL',
        callback: (value) => `${separateNumber(r(value, 0))} ₽`,
        reverse: true,
    },
    purchases: {
        title: 'Продажи',
        callback: (value) => separateNumber(r(value, 0)),
    },
    income: {
        title: 'Доход',
        callback: (value) => `${separateNumber(r(value, 0))} ₽`,
    },
    drr: {
        title: 'ДРР',
        callback: (value) => `${separateNumber(r(value, 2))} %`,
        reverse: true,
    },
    // impressions: {
    //     title: 'Показы',
    //     callback: (value) => separateNumber(r(value, 0)),
    //     reverse: false,
    // },
    // ctr: {
    //     title: 'CTR',
    //     callback: (value) => `${separateNumber(r(value, 2))} %`,
    //     reverse: false,
    // },
};

const fields = computed(() => {
    if (isEmpty2(props.fieldsSettings)) {
        return HEADER_FIELDS;
    }

    let res = {};

    let orderedList;
    if (!(props.fieldsSettings instanceof Array)) {
        orderedList = Object.keys(props.fieldsSettings)
            .sort((a, b) => props.fieldsSettings[a] - props.fieldsSettings[b])
    } else {
        orderedList = props.fieldsSettings;
    }

    for (let key of orderedList) {
        if (!isEmpty2(HEADER_FIELDS[key])) {
            res[key] = HEADER_FIELDS[key];
        }
    }

    return res;
});

const sort = ref({
    field: 'expenses',
    direction: false,
});

const inLoading = ref(false);
const sortProxy = computed({
    get: () => sort.value,
    set: (val) => {
        inLoading.value = true;

        setTimeout(() => {
            sort.value = val;
            inLoading.value = false;
        }, 0);
    }
});
</script>
