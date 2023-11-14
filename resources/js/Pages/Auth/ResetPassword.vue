<template>
    <Head>
        <title>{{ $t('auth.reset_pass.title') }}</title>
    </Head>
    <h1 class="title">{{ $t('auth.reset_pass.header') }}</h1>

    <BreezeValidationErrors class="mb-4"/>

    <form @submit.prevent="submit">
        <input type="hidden" v-model="form.email" required/>

        <div class="form-field mb-3">
            <input
                type="password"
                class="form-control"
                v-model="form.password"
                :placeholder="$t('auth.reset_pass.form.new_pass') + '*'"
                required
                autofocus
                autocomplete="new-password"
            >
        </div>

        <div class="form-field mb-3">
            <input
                type="password"
                class="form-control"
                v-model="form.password_confirmation"
                :placeholder="$t('auth.reset_pass.form.confirm_pass') + '*'"
                required
                autocomplete="new-password"
            >
        </div>

        <div class="flex items-center justify-end mt-4">
            <button
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                class="btn btn-block btn-primary"
            >
                {{ $t('auth.reset_pass.reset_pass') }}
            </button>
        </div>
    </form>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head } from '@inertiajs/vue3';

export default {
    layout: BreezeGuestLayout,

    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
    },

    props: {
        email: String,
        token: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                token: this.token,
                email: this.email,
                password: '',
                password_confirmation: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.update'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>
