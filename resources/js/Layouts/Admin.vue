<template>
    <div>
        <toast-messages/>
        <div class="min-h-screen bg-background flex flex-col md:flex-row">
            <!-- Sidebar -->
            <div class="md:w-sidebar bg-sidebar">
                <div class="h-desktop_header flex justify-between md:justify-center px-4 items-center">
                    <div class="flex flex-row flex-nowrap">
                        <Link
                            class="btn btn-md btn-primary mr-3"
                            :href="route('admin.users.create.show')"
                        >
                            {{ $t('admin.sidebar.newUserButton') }}
                        </Link>
<!--                        <button class="btn btn-md btn-outline-primary">{{ $t('admin.sidebar.requests') }}</button>-->
                    </div>

                    <div class="md:hidden"
                         @click="$refs.sidebar.classList.toggle('hidden')"
                    ><img src="/icons/hamburger.svg" class="w-9 h-9" alt="menu"/></div>
                </div>
                <div
                    class="md:block hidden pt-8"
                    ref="sidebar"
                >
                    <admin-sidebar/>
                </div>
            </div>

            <!-- Page Content -->
            <main class="bg-background flex-1 overflow-x-hidden">
                <header class="bg-white h-desktop_header flex items-center px-4 app-header">
                    <admin-header/>
                </header>
                <div class="py-7 px-9">
                    <slot/>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import AdminHeader from "@/Pages/Admin/Components/AdminHeader.vue";
import AdminSidebar from "@/Pages/Admin/Components/AdminSidebar.vue";
import ToastMessages from "@/Components/ToastMessages.vue";
import {Link} from "@inertiajs/vue3";

</script>
