<template>
    <index
        :widgets="widgets"
        :project="project"
        :selected-widget="selectedWidget"
    >
        <div class="grid lg:grid-cols-2 grid-cols-1">
            <form class="space-y-4" @submit.prevent="onSubmit">
                <div class="card px-4 text-sm">
                    <div class="bg-gray-500/10 z-10 absolute h-full w-full top-0 left-0"
                         v-if="!form.wa_enabled"
                    ></div>

                    <div class="flex items-center">
                        <h3 class="text-lg flex-shrink">WhatsApp</h3>
                        <div class="flex-grow"></div>
                        <toggle-switch v-model:checked="form.wa_enabled" class="z-20 mr-2"/>
                    </div>

                    <div class="mt-3 space-y-4">
                        <div>
                            <label class="form-label">Укажите Ваш номер телефона</label>
                            <input
                                class="form-control-sm w-full"
                                type="text"
                                v-model="form.wa_phone"
                                :required="form.wa_enabled"
                            />
                            <input-error :message="form.errors.wa_phone"/>
                            <p class="text-xs p-1 text-gray-500">В формате 79000000000</p>
                        </div>
                        <div>
                            <label class="form-label">Приветственное сообщение</label>
                            <input
                                class="form-control w-full"
                                type="text"
                                v-model.trim="form.wa_message"
                                placeholder="Введите приветственное сообщение"
                            />
                            <input-error :message="form.errors.wa_message"/>
                        </div>
                        <div>
                            <label class="form-label">Перенаправление для пользователей ПК</label>
                            <select
                                class="w-full select-control"
                                v-model="form.wa_redirect_type"
                            >
                                <option value="web">Web-версия (web.whatsapp.com)</option>
                                <option value="api">Приложения (api.whatsapp.com)</option>
                            </select>
                            <input-error :message="form.errors.wa_redirect_type"/>
                        </div>
                    </div>
                </div>

                <div class="card px-4 text-sm">
                    <div class="bg-gray-500/10 z-10 absolute h-full w-full top-0 left-0"
                         v-if="!form.tg_enabled"
                    ></div>

                    <div class="flex items-center">
                        <h3 class="text-lg flex-shrink">Telegram</h3>
                        <div class="flex-grow"></div>
                        <toggle-switch v-model:checked="form.tg_enabled" class="z-20 mr-2"/>
                    </div>

                    <div class="mt-3 space-y-4">
                        <p class="text-sm">Укажите имя пользователя аккаунта Telegram без @,
                            в который будут поступать сообщения от клиентов. (например imtera_bot)</p>
                        <div>
                            <input
                                class="form-control-sm w-full"
                                type="text"
                                v-model="form.tg_nickname"
                                :required="form.tg_enabled"
                            />
                            <input-error :message="form.errors.tg_nickname"/>
                        </div>
                        <p class="text-sm text-gray-500" v-if="false">Сквозная аналитика работает только с ботами</p>
                        <for-admins>
                            <toggle-switch v-model:checked="form.tg_dont_create_leads">
                                Не создавать сделки в CRM
                            </toggle-switch>
                        </for-admins>
                    </div>
                </div>

                <div class="py-2">
                    <button type="submit" class="btn btn-md btn-primary">Сохранить</button>
                </div>

                <div class="card px-4 text-sm space-y-4">
                    <h3 class="text-lg flex-shrink">Установите код на сайт</h3>
                    <p class="text-sm">Чтобы принимать сообщения от клиентов установите код на все страницы сайта, перед
                        закрывающим тегом &lt;head&gt;</p>
                    <textarea
                        class="form-control text-xs"
                        ref="elCode"
                        @click="$event.target.select()"
                        readonly
                    >{{widgetCode}}</textarea>

                    <div class="flex justify-end">
                        <button
                            class="btn btn-md btn-outline-primary"
                            @click="showCodeSendModal = true"
                            type="button"
                        >Отправить программисту
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </index>

    <modal :show="showCodeSendModal" @close="showCodeSendModal = false" max-width="sm" closeable>
        <loading-block
            v-if="sendCodeForm.processing"
            class="bg-gray-500/20"
            spinner-dark
        />
        <form class="p-4" @submit.prevent="onSendCodeSubmit">
            <h3 class="text-lg mb-4">Отправка кода программисту</h3>
            <div class="form-field">
                <label class="form-label">E-Mail</label>
                <text-input
                    placeholder="Введите почту программиста"
                    v-model="sendCodeForm.email"
                    required
                    autocomplete="email"
                />
            </div>
            <div class="form-field mt-4">
                <button type="submit" class="btn btn-md btn-primary w-full">Отправить</button>
            </div>
        </form>
    </modal>
</template>

<script setup>
import Index from "@/Pages/SocialWidget/Index.vue";
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import InputError from "@/Components/InputError.vue";
import {onMounted, ref} from "vue";
import ForAdmins from "@/Components/ForAdmins.vue";
import Modal from "@/Components/Modal/Modal.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import LoadingBlock from "@/Components/LoadingBlock.vue";

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
    widgetCode: {
        type: String,
        required: true,
    },
});

const showCodeSendModal = ref(false);
const sendCodeForm = useForm({
    email: '',
});

function onSendCodeSubmit() {
    sendCodeForm.post(route('social-widget.private.settings.channels.send-code', [props.project.id, props.selectedWidget.id]), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => showCodeSendModal.value = false,
    });
}

const elCode = ref(null);
onMounted(() => {
    // Авто-подгонка размера textarea
    elCode.value.style.height = '5px';
    elCode.value.style.height = elCode.value.scrollHeight + 5 + 'px';
});

const form = useForm(props.selectedWidget.messengers_settings);

function onSubmit() {
    form.put(route('social-widget.private.settings.channels.save', [props.project, props.selectedWidget]));
}
</script>
