<template>
    <Head>
        <title>{{ $t('auth.login.metaTitle') }}</title>
    </Head>
    <h1 class="title">{{ $t('auth.login.title', {appName: $page.props.app.name}) }}</h1>

    <p class="text-info-dark">{{ status }}</p>
    <BreezeValidationErrors class="mb-4"/>

    <form @submit.prevent="submit">
 
        <div class="form-field mb-3">
            <input v-model="form.email" class="form-control" name="email" type="email"
                   :placeholder="$t('auth.login.fields.email') + '*'" required
                   autofocus autocomplete="username">
        </div>

        <div class="form-field has-pass-toggler mb-3">
            <password-input v-model="form.password" :placeholder="$t('auth.login.fields.pass') + '*'" required/>

            <div class="login-links flex justify-between">
                <NavLink :href="route('register')">{{ $t('auth.login.register') }}</NavLink>
                <NavLink :href="route('password.request')">{{ $t('auth.login.forgotPass') }}</NavLink>
            </div>
        </div>

        <button type="submit" class="btn btn-block btn-outline-primary mb-6" :disabled="form.processing">
            {{ $t('auth.login.login') }}
        </button>
    </form>

    <GoogleAuthBtn :title="$t('auth.login.byGoogle')"/>

    <ElamaAuthBtn :title="$t('auth.login.byGoogle')"/>
</template>

<script>
import Layout from '@/Layouts/Guest.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import {Head, Link} from '@inertiajs/vue3';
import NavLink from "@/Components/NavLink.vue";
import InputError from "@/Components/InputError.vue";
import PasswordInput from "@/Components/PasswordInput.vue";
import GoogleAuthBtn from "@/Components/GoogleAuthBtn.vue";
import ElamaAuthBtn from '@/Components/ElamaAuthBtn.vue';

export default {
    layout: Layout,

    components: {
    GoogleAuthBtn,
    ElamaAuthBtn,
    PasswordInput,
    InputError,
    NavLink,
    BreezeValidationErrors,
    Head,
    Link,
    ElamaAuthBtn
},

    props: {
        canResetPassword: Boolean,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: true,
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('login'), {
                onFinish: () => this.form.reset('password'),
            })
        }
    }
}
</script>

<style scoped>

</style>
