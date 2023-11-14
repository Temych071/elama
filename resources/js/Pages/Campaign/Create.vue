<template>
    <Head>
        <title>{{ $t('campaigns.settings.create.title') }}</title>
    </Head>

    <div class="card-md card-dialog md-w-auto-full m-auto">
        <h1 class="card-title-d">{{ $t('campaigns.settings.create.header') }}</h1>

        <div class="mt-10">
            <validation-errors/>
            <form @submit.prevent="submit">
                <div class="form-field">
                    <text-input v-model="form.name" :placeholder="$t('campaigns.settings.create.placeholder')"/>
                </div>
                <div class="form-field mt-8 flex flex-row-reverse">
                    <button class="btn btn-md btn-primary" type="submit">{{
                            $t('campaigns.settings.create.button')
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
import ValidationErrors from "@/Components/ValidationErrors.vue";
import {Head} from '@inertiajs/vue3';
import {ymReachGoal} from "@/utils";

export default {
    components: {ValidationErrors, TextInput, Head},
    layout: Layout,
    data() {
        return {
            form: this.$inertia.form({
                name: '',
            }),
        };
    },
    methods: {
        submit() {
            ymReachGoal('project-settings-save');
            this.form.post(route('campaign.create.store'));
        },
    },
}
</script>
