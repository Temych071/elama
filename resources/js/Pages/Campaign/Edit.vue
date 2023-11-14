<template>
    <Head>
        <title>{{ $t('campaigns.settings.edit.title') }}</title>
    </Head>

    <div class="card-md card-dialog">
        <h1 class="card-title-d">{{ $t('campaigns.settings.edit.header') }}</h1>

        <div class="mt-10">
            <validation-errors/>
            <form @submit.prevent="submit">
                <div class="form-field">
                    <text-input v-model="form.name" :placeholder="$t('campaigns.settings.edit.placeholder')"/>
                </div>
                <div class="form-field mt-8 flex flex-row-reverse">
                    <button class="btn btn-md btn-primary" type="submit">{{
                            $t('campaigns.settings.edit.button')
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
import {Head} from '@inertiajs/vue3';
import ValidationErrors from "@/Components/ValidationErrors.vue";

export default {
    components: {ValidationErrors, TextInput, Head},
    layout: Layout,
    props: {
        campaign: Array,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.campaign.name,
            }),
        };
    },
    methods: {
        submit() {
            this.form.post('');
        },
    },
}
</script>
