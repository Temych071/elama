<template>
    <Head>
        <title>Настройки уведомлений</title>
    </Head>

    <div class="card-md card-dialog">
        <h1 class="card-title-d mb-6">Уведомления</h1>
        <article class="mb-3">
            <label class="title-sm mb-4">Telegram</label>
            <Checkbox v-model:checked="formTelegram.telegramNotification">Настройка</Checkbox>

            <div v-if="formTelegram.telegramNotification" class="mt-2">
                <p class="text-sm font-normal leading-none my-2">
                    1. Подключить Телеграм бота
                    <a class="text-sky font-semibold underline" :href="`https://t.me/${$page.props.app.config.telegram.bot_username}`" target="_blank">Daily Grow - Поддержка</a>
                    <br>
                    2. Скопировать полученный код и вставить в поле
                    <br>
                    3. Нажать Подписаться на уведомления
                </p>
                <label class="mb-4">Введите полученный код</label>
                <text-input :disabled="enableTelegram" v-model.trim="formTelegram.telegramCode"/>
                <div v-if="enableTelegram" class="mt-2 text-sm text-success-dark">Уведомления включены</div>
                <validation-errors/>

                <div class="flex justify-between">
                    <button class="btn mt-2" @click="unsetTelegram" :disabled="!enableTelegram">
                        Отключить уведомления
                    </button>
                    <button class="btn btn-md btn-primary mt-2" :disabled="enableTelegram" @click="setTelegram">
                        Подписаться на уведомления
                    </button>
                </div>
            </div>
        </article>

        <article class="mb-3">
            <label class="title-sm mb-4">Почта</label>
            <Checkbox v-model:checked="formEmail.emailNotifications">Настройка</Checkbox>

            <div v-if="formEmail.emailNotifications" class="mt-2">
                <label class="mb-4">Введите почту для получения уведомлений</label>
                <text-input :disabled="enableEmail" v-model.trim="formEmail.emailAddress"/>
                <validation-errors/>

                <div class="flex justify-between">
                    <button class="btn mt-2" @click="unsetEmail" :disabled="!enableEmail">
                        Отключить уведомления
                    </button>
                    <button class="btn btn-md btn-primary mt-2" :disabled="enableEmail" @click="setEmail">
                        Подписаться на уведомления
                    </button>
                </div>
            </div>
        </article>
    </div>

</template>

<script>
import Layout from '@/Layouts/Authenticated.vue';
import {Head, useForm} from '@inertiajs/vue3';
import ValidationErrors from "@/Components/ValidationErrors.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Label from "@/Components/Label.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import FormButton from "@/Components/Forms/FormButton.vue";
import Button from "@/Components/Button.vue";

export default {
    name: "Setting",
    layout: Layout,
    components: {
        Head, ValidationErrors, TextInput, Checkbox, Label, FormButton, Button
    },
    props: {
        enableTelegram: {type: Boolean, required: true},
        codeTelegram: {type: String, default: null},
        enableEmail: {type: Boolean, default: false},
        emailAddress: {type: String, default: null},
    },
    methods: {
        setTelegram() {
            // console.log(this.formTelegram);
            this.formTelegram.post(route('user.settings_notifications.set_telegram_notification'))
        },
        unsetTelegram() {
            this.$inertia.delete(this.route('user.settings_notifications.unset_telegram_notification'), {
                onSuccess: () => {
                    this.formTelegram.reset();
                    this.formTelegram.telegramCode = null;
                    this.formTelegram.telegramNotification = false;
                },
            })
        },

        setEmail() {
            this.formEmail.post(this.route('user.settings_notifications.set_email_notification'))
        },
        unsetEmail() {
            this.$inertia.delete(this.route('user.settings_notifications.unset_email_notification'), {
                onSuccess: () => {
                    this.formEmail.reset();
                    this.formEmail.emailAddress = this.emailAddress;
                    this.formEmail.emailNotifications = false;
                },
            })
        }
    },
    data() {
        return {
            formTelegram: useForm({
                telegramNotification: this.enableTelegram,
                telegramCode: this.codeTelegram,
            }),
            formEmail: useForm({
                emailNotifications: this.enableEmail,
                emailAddress: this.emailAddress,
            })
        }
    }
}
</script>

<style scoped>

</style>
