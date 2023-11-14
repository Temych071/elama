<template>
    <index
        :widgets="widgets"
        :project="project"
        :selected-widget="selectedWidget"
    >
        <div class="grid grid-cols-4">
            <div class="lg:col-span-3 col-span-4 space-y-8">
                <form class="grid gap-x-8 gap-y-4 md:grid-cols-2 grid-cols-1" @submit.prevent="onStatsSubmit">
                    <div class="card px-4 text-sm">
                        <div class="bg-gray-500/10 z-10 absolute h-full w-full top-0 left-0"
                             v-if="!statsForm.ym_enabled"
                        ></div>

                        <div class="flex items-center">
                            <h3 class="text-lg flex-shrink flex items-center space-x-2">
                                <img alt="ym" src="/icons/campaign/y_metrika.svg" class="w-4" />
                                <span>Яндекс.Метрика</span>
                            </h3>
                            <div class="flex-grow"></div>
                            <toggle-switch v-model:checked="statsForm.ym_enabled" class="z-20 mr-2"/>
                        </div>

                        <div class="flex xl:flex-row flex-col xl:items-center mt-2 gap-1">
                            <span class="xl:w-1/2">Введите номер счётчика</span>
                            <input
                                type="text"
                                v-model.trim="statsForm.ym_counter_id"
                                class="form-control-sm xl:w-1/2 w-full"
                                placeholder="12346578"
                            />
                            <div class="col-span-2">
                                <input-error :message="statsForm.errors.ym_counter_id" />
                            </div>
                        </div>

                        <hr class="my-2">

                        <p class="link cursor-pointer" @click="showGoalsList = !showGoalsList">Список целей</p>
                        <div class="grid grid-cols-2 mt-2" v-if="showGoalsList">
                            <p class="font-bold">Событие</p> <p class="font-bold">Идентификатор</p>
                            <p>Открытие виджета</p>          <p>dg_social_open</p>
                            <p>Переход в Telegram</p>        <p>dg_social_click_tg</p>
                            <p>Переход в WhatsApp</p>        <p>dg_social_click_wa</p>
                        </div>
                    </div>
                    <div class="card px-4 text-sm">
                        <div class="bg-gray-500/10 z-10 absolute h-full w-full top-0 left-0"
                             v-if="!statsForm.ga_enabled"
                        ></div>

                        <div class="flex items-center">
                            <h3 class="text-lg flex-shrink flex items-center space-x-2">
                                <img alt="ym" src="/icons/campaign/google_analytics.svg" class="w-4" />
                                <span>Google Analytics</span>
                            </h3>
                            <div class="flex-grow"></div>
                            <toggle-switch v-model:checked="statsForm.ga_enabled" class="z-20 mr-2"/>
                        </div>

                        <div class="flex xl:flex-row flex-col xl:items-center mt-2 gap-1">
                            <span class="xl:w-1/2">Введите номер счётчика</span>
                            <input
                                type="text"
                                v-model.trim="statsForm.ga_counter_id"
                                class="form-control-sm xl:w-1/2 w-full"
                                placeholder="UA-12345678-1"
                            />
                            <div class="col-span-2">
                                <input-error :message="statsForm.errors.ga_counter_id" />
                            </div>
                        </div>

                        <hr class="my-2">

                        <p class="link cursor-pointer" @click="showGoalsList = !showGoalsList">Список целей</p>
                        <div class="grid grid-cols-2 mt-2" v-if="showGoalsList">
                            <p class="font-bold">Событие</p> <p class="font-bold">Идентификатор</p>
                            <p>Открытие виджета</p>          <p>dg_social_open</p>
                            <p>Переход в Telegram</p>        <p>dg_social_click_tg</p>
                            <p>Переход в WhatsApp</p>        <p>dg_social_click_wa</p>
                        </div>
                    </div>
                    <div class="col-span-full">
                        <button
                            type="submit"
                            class="btn btn-md btn-primary"
                        >Сохранить</button>
                    </div>
                </form>

                <for-admins>
                    <form class="grid gap-x-8 gap-y-4 md:grid-cols-2 grid-cols-1" @submit.prevent="onCrmSubmit">
                        <div class="card px-4 text-sm">
                            <div class="bg-gray-500/10 z-10 absolute h-full w-full top-0 left-0"
                                 v-if="!crmForm.bx_enabled"
                            ></div>

                            <div class="flex items-center">
                                <h3 class="text-lg flex-shrink flex items-center space-x-2">
                                    <img alt="ym" src="/icons/social-widget/bitrix.png" class="w-4" />
                                    <span>Битрикс24</span>
                                </h3>
                                <div class="flex-grow"></div>
                                <toggle-switch v-model:checked="crmForm.bx_enabled" class="z-20 mr-2"/>
                            </div>

                            <div class="space-y-4 mt-4">
                                <div class="form-field">
                                    <p class="form-label">Вставьте ссылку на аккаунт Битрикс24</p>
                                    <input
                                        type="text"
                                        v-model.trim="crmForm.bx_webhook_url"
                                        class="form-control-sm w-full"
                                        placeholder="https://***.bitrix24.ru/rest/***/***/"
                                    />
                                    <input-error :message="crmForm.errors.bx_webhook_url" />
                                </div>
                                <template v-if="false">
                                    <div class="form-field">
                                        <p class="form-label">Ответственный</p>
                                        <input
                                            type="text"
                                            v-model.trim="crmForm.bx_responsible_id"
                                            class="form-control-sm w-full"
                                        />
                                        <input-error :message="crmForm.errors.bx_responsible_id" />
                                    </div>
                                    <div class="form-field">
                                        <p class="form-label">Воронка продаж</p>
                                        <input
                                            type="text"
                                            v-model.trim="crmForm.bx_kanban_id"
                                            class="form-control-sm w-full"
                                        />
                                        <input-error :message="crmForm.errors.bx_kanban_id" />
                                    </div>
                                </template>
                            </div>
                            <template v-if="false">
                                <hr class="my-4"/>
                                <table class="mt-3 w-full">
                                    <tr>
                                        <td>Дополнительно</td>
                                        <td>
                                            <toggle-switch
                                                v-model:checked="crmForm.bx_send_utms"
                                            >Передавать UTM метки</toggle-switch>
                                        </td>
                                    </tr>
                                </table>
                            </template>
                        </div>

                        <div class="card px-4 text-sm">
                            <div class="bg-gray-500/10 z-10 absolute h-full w-full top-0 left-0"
                                 v-if="!crmForm.amo_enabled"
                            ></div>

                            <div class="flex items-center">
                                <h3 class="text-lg flex-shrink flex items-center space-x-2">
                                    <img alt="ym" src="/icons/social-widget/bitrix.png" class="w-4" />
                                    <span>AmoCRM</span>
                                </h3>
                                <div class="flex-grow"></div>
                                <toggle-switch v-model:checked="crmForm.amo_enabled" class="z-20 mr-2"/>
                            </div>

                            <a
                                v-if="!selectedWidget.amo_connected"
                                class="mt-4 btn btn-sm btn-primary"
                                :href="route('social-widget.private.settings.integrations.amo-auth', [selectedWidget.project_id, selectedWidget])"
                            >Подключить</a>
                            <a
                                v-else
                                class="mt-4 btn btn-sm btn-primary disabled"
                            >Подключено</a>

                            <div class="space-y-4 mt-4" v-if="false">
                                <div class="form-field">
                                    <p class="form-label">Ответственный</p>
                                    <input
                                        type="text"
                                        v-model.trim="crmForm.amo_responsible_id"
                                        class="form-control-sm w-full"
                                    />
                                    <input-error :message="crmForm.errors.amo_responsible_id" />
                                </div>
                                <div class="form-field">
                                    <p class="form-label">Воронка продаж</p>
                                    <input
                                        type="text"
                                        v-model.trim="crmForm.amo_kanban_id"
                                        class="form-control-sm w-full"
                                    />
                                    <input-error :message="crmForm.errors.amo_kanban_id" />
                                </div>
                            </div>
                            <template v-if="false">
                                <hr class="my-4"/>
                                <table class="mt-3 w-full">
                                    <tr>
                                        <td>Дополнительно</td>
                                        <td>
                                            <toggle-switch
                                                v-model:checked="crmForm.amo_send_utms"
                                            >Передавать UTM метки</toggle-switch>
                                        </td>
                                    </tr>
                                </table>
                            </template>
                        </div>
                        <div class="col-span-full">
                            <button
                                type="submit"
                                class="btn btn-md btn-primary"
                            >Сохранить</button>
                        </div>
                    </form>
                </for-admins>
            </div>
        </div>
    </index>
</template>

<script setup>
import Index from "@/Pages/SocialWidget/Index.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import {ref, watch} from "vue";
import ForAdmins from "@/Components/ForAdmins.vue";

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

const showGoalsList = ref(false);

const statsForm = useForm(props.selectedWidget.stats_integrations_settings);

function onStatsSubmit() {
    console.log(statsForm.data())
    statsForm.put(route('social-widget.private.settings.integrations.saveStats', [props.project, props.selectedWidget]));
}

const crmForm = useForm(props.selectedWidget.crm_integrations_settings);
function onCrmSubmit() {
    crmForm.put(route('social-widget.private.settings.integrations.saveCrm', [props.project, props.selectedWidget]));
}

watch(() => crmForm.amo_enabled, () => {
    if (crmForm.amo_enabled) {
        crmForm.bx_enabled = false;
    }
});

watch(() => crmForm.bx_enabled, () => {
    if (crmForm.bx_enabled) {
        crmForm.amo_enabled = false;
    }
});
</script>
