<template>
    <page-title lang="auth.verify_email.title"/>
    <guest>
        <h1 class="title">{{ $t('auth.verify_email.header') }}</h1>

        <div class="mb-4 text-sm">
            {{ $t('auth.verify_email.text') }}

        </div>

        <div class="mb-4 font-medium text-sm text-success-dark" v-if="verificationLinkSent">
            {{ $t('auth.verify_email.send_again_msg') }}
        </div>

        <form @submit.prevent="onSubmit">
            <button
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                class="btn btn-block btn-primary mb-6"
            >
                {{ $t('auth.verify_email.send_again') }}
            </button>

            <Link :href="route('logout')" method="post" class="btn btn-block btn-outline-primary">
                {{ $t('auth.verify_email.logout') }}
            </Link>
        </form>
    </guest>
</template>

<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import {ymReachGoal} from "@/utils";
import PageTitle from "@/Components/PageTitle.vue";
import Guest from "@/Layouts/Guest.vue";
import {computed} from "vue";

const props = defineProps({
    status: {
        type: String,
        required: false,
        default: null,
    },
});

const form = useForm({});

function onSubmit() {
    ymReachGoal('on-registered');
    form.post(route('verification.send'));
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>
