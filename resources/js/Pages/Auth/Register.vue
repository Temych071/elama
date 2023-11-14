<template>
    <Head>
        <title>{{ $t('auth.reg.metaTitle') }}</title>
    </Head>

    <h1 class="title">{{ $t('auth.reg.title', {appName: $page.props.app.name}) }}</h1>

    <form @submit.prevent="submit">

        <div class="form-field mb-3">
            <input
                v-model="form.name"
                class="form-control"
                name="name"
                type="text"
                :placeholder="$t('auth.reg.fields.name')"
                autocomplete="name"
            >
            <input-error :message="form.errors.name"/>
        </div>

        <div class="form-field mb-3">
            <text-input
                v-model:modelValue="form.phone"
                name="phone"
                type="tel"
                :placeholder="$t('auth.reg.fields.phone') + '*'"
                required
                autocomplete="tel"
                :error="form.errors.phone"
            ></text-input>
        </div>

        <div class="form-field mb-3">
            <input
                v-model="form.email"
                class="form-control"
                name="email"
                type="email"
                :placeholder="$t('auth.reg.fields.email') + '*'"
                required
                autofocus
                autocomplete="username"
            >
            <input-error :message="form.errors.email"/>
        </div>

        <div class="form-field has-pass-toggler mb-3">
            <password-input
                v-model="form.password"
                :placeholder="$t('auth.reg.fields.pass') + '*'"
                required
            />
            <input-error :message="form.errors.password"/>
        </div>

        <div class="form-field mb-3">
            <checkbox v-model:checked="form.terms" name="terms">
                <p class="checkbox-label" v-html="$t('auth.reg.agreement')"></p>
            </checkbox>
            <input-error :message="form.errors.terms"/>
        </div>

        <button type="submit" class="btn btn-block btn-outline-primary mb-6" :disabled="form.processing">
            {{ $t('auth.reg.register') }}
        </button>
    </form>

    <GoogleAuthBtn :title="$t('auth.reg.byGoogle')"/>

    <div class="text-center mt-6 text-sm">
        <span class="text-gray-dark">{{ $t('auth.reg.loginLink.prefix') }}</span>
        <NavLink :href="route('login')" class="text-primary-dark">{{ $t('auth.reg.loginLink.linkText') }}</NavLink>
    </div>
</template>

<style scoped lang="scss">
.checkbox-label {
    @apply font-normal leading-4 pl-2;

    font-size: .625rem;
    color: #BFC3CE;
    letter-spacing: .1px;
}
</style>

<script>
import Layout from '@/Layouts/Guest.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import {Head} from '@inertiajs/vue3';
import NavLink from "@/Components/NavLink.vue";
import InputError from "@/Components/InputError.vue";
import PasswordInput from "@/Components/PasswordInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import GoogleAuthBtn from "@/Components/GoogleAuthBtn.vue";

export default {
    layout: Layout,

    components: {
        GoogleAuthBtn,
        TextInput,
        PasswordInput,
        InputError,
        NavLink,
        Checkbox,
        BreezeValidationErrors,
        Head,
    },

    props: {
        canResetPassword: Boolean,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: '',
                phone: '+7',
                password: '',
                name: '',
                terms: false,
                remember: true,
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('register'), {
                onFinish: () => this.form.reset('password'),
            })
        }
    }
}
</script>
