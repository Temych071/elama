<template>
    <Head>
        <title>{{ $t('campaigns.settings.delete.title') }}</title>
    </Head>

    <div class="card-md card-dialog">
        <h1 class="card-title-d">{{ $t('campaigns.settings.delete.header') }}</h1>

        <div class="mt-10">
            <form @submit.prevent="submit">
                <div class="form-field">
                    <text-input disabled :model-value="campaign.name"/>
                </div>
                <div class="form-field mt-8 flex flex-row-reverse">
                    <button class="btn btn-md btn-primary" type="submit">{{
                            $t('campaigns.settings.delete.button')
                        }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '@/Layouts/Authenticated.vue';
import TextInput from "@/Components/Forms/TextInput.vue";
import {useForm, Head} from '@inertiajs/vue3';

export default {
    components: {TextInput, Head},
    layout: Layout,
    props: {
        campaign: Array,
    },
    methods: {
        submit() {
            useForm({}).post('');
        },
    },
}
</script>
