<template>
    <Head>
        <title>{{ $t('auth.forgot_pass.title') }}</title>
    </Head>
    <h1 class="title">{{ $t('auth.forgot_pass.header') }}</h1>

    <div class="mb-4 text-sm">
        {{ $t('auth.forgot_pass.text') }}
    </div>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <BreezeValidationErrors class="mb-4"/>

    <form @submit.prevent="submit">
        <div class="form-field mb-3">
            <input
                type="email"
                class="form-control"
                v-model="form.email"
                :placeholder="$t('auth.forgot_pass.form.email') + '*'"
                required
                autofocus
                autocomplete="email"
            >
        </div>

        <button
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            class="btn btn-block btn-primary"
        >
            {{ $t('auth.forgot_pass.send') }}
        </button>
    </form>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import {Head} from '@inertiajs/vue3';

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
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: ''
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.email'))
        }
    }
}
</script>
