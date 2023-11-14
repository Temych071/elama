<template>
    <Head>
        <title>{{ $t('auth.reg_finish.title') }}</title>
    </Head>

    <h1 class="title">{{ $t('auth.reg.title', {appName: $page.props.app.name}) }}</h1>

    <form method="POST" @submit.prevent="submit">
        <div class="form-field mb-3">
            <text-input
                v-model:modelValue="form.phone"
                name="phone"
                type="tel"
                :placeholder="$t('auth.reg_finish.form.phone') + '*'"
                required
                autocomplete="tel"
                :error="form.errors.phone"
                mask="tel"
            ></text-input>
        </div>

        <div class="form-field has-pass-toggler mb-3">
            <password-input
                v-model="form.password"
                name="password"
                :placeholder="$t('auth.reg_finish.form.pass') + '*'"
                required
            />
            <input-error :message="form.errors.password"></input-error>
        </div>

        <input-error :message="form.errors.business_error"></input-error>
        <button class="btn btn-block btn-outline-primary mb-6">{{ $t('auth.reg_finish.finish_register') }}</button>

        <nav-link as="button" :href="route('logout')" method="post" class="w-full text-center text-sm text-gray-400">
            Выйти
        </nav-link>
    </form>
</template>

<script>
import Layout from '@/Layouts/Guest.vue';
import {Head} from '@inertiajs/vue3';
import PasswordInput from "@/Components/PasswordInput.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import {ymReachGoal} from "@/utils";
import NavLink from "@/Components/NavLink.vue";

export default {
    components: {NavLink, TextInput, InputError, PasswordInput, Head},
    layout: Layout,
    name: "RegisterFinish",

    data() {
        return {
            form: this.$inertia.form({
                phone: '+7',
                password: '',
            })
        }
    },

    methods: {
        submit() {
            ymReachGoal('on-registered');
            this.form.post(this.route('register-finish.send'), {
                onFinish: () => this.form.reset('password'),
            })
        }
    }
}
</script>
