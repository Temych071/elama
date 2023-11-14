<template>
    <div>
        <toast-messages/>
        <div class="min-h-screen bg-background flex flex-col md:flex-row">
            <!-- Sidebar -->
            <div
                class="bg-sidebar sidebar-container w-full"
                :class="{'md:w-sidebar': !isSidebarCollapsed, 'md:w-sidebar_collapsed': isSidebarCollapsed}"
                v-if="isProjectSelected"
            >
                <button
                    class="sidebar-container__collapse-btn"
                    @click="isSidebarCollapsed = !isSidebarCollapsed">
                    <svg :class="{'rotate-180': isSidebarCollapsed}" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.625 12.25L1.375 7L6.625 1.75"
                              stroke="white"
                              stroke-width="2.48712"
                              stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </button>

                <div
                    class="h-desktop_header flex justify-center px-7 items-center border-b border-gray-100">
                    <!--                    <span class="text-white text-logo font-bold font-logo">{{ $page.props.app.name }}</span>-->
                    <nav-link :href="route('campaign.index')">
                        <application-logo class="hidden md:block" :small="isSidebarCollapsed" />
                        <application-logo class="md:hidden block" />
                    </nav-link>
                </div>

                <div class="md:block" :class="{'hidden': isSidebarCollapsed}" ref="sidebar">
                    <slot name="sidebar">
                        <app-sidebar
                            :is-collapsed="isSidebarCollapsed"
                            :project="selectedProject"
                        />
                    </slot>
                </div>
            </div>

            <!-- Page Content -->
            <main class="bg-background flex-1 overflow-hidden">
                <header class="bg-white md:h-desktop_header h-auto flex items-center px-4 app-header">
                    <slot name="header">
                        <app-header :is-collapsed="isSidebarCollapsed"/>
                    </slot>
                </header>
                <div class="md:py-10 md:px-9 py-6 px-6">
                    <slot/>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import AppHeader from "@/Components/AppHeader.vue";
import AppSidebar from "@/Components/AppSidebar.vue";
import ToastMessages from "@/Components/ToastMessages.vue";
import {computed, ref, watch} from "vue";
import {isEmpty2, n} from "@/utils.js";
import {usePage} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";

const isSidebarCollapsed = ref(!!JSON.parse(window.localStorage.getItem('sidebar.is_collapsed')));
watch(isSidebarCollapsed, (v) => window.localStorage.setItem('sidebar.is_collapsed', v));

const pageProps = computed(() => usePage().props);

const selectedProject = computed(() =>
    pageProps.value.page_project
    ?? pageProps.value.campaigns?.find((item) => item?.id === selectedProjectId.value) ?? null);

const isProjectSelected = computed(() => !isEmpty2(selectedProject.value));
const selectedProjectId = computed(() => n(
    pageProps.value.page_project?.id
    ?? pageProps.value.activeCampaignId
    ?? pageProps.value.campaignId
    ?? route()?.params?.campaign
));

</script>
