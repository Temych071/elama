<template>
    <Head>
        <title>{{ $t('helpPage.title') }}</title>
    </Head>

    <div class="grid lg:grid-cols-2 grid-cols-1 dashboard-content">
        <div>
            <h2 class="font-black mb-3">{{ $t('helpPage.header') }}</h2>
            <div class="flex flex-row">
                <div class="pr-6 pt-2 flex-shrink-0">
                    <img class="w-12 rounded-full" alt="photo" src="/images/DenisT.svg">
                </div>
                <div class="flex-grow max-w-sm">
                    <p class="mb-3">{{ $t('helpPage.welcome') }}</p>
                    <p class="mb-3 text-sm">
                        {{ $t('helpPage.infoText') }}
                    </p>
                    <p class="text-sm">{{ $t('helpPage.infoAuthor') }}</p>
                </div>
            </div>
            <div class="py-8">
                <iframe class="w-full guide-video"
                        src="https://www.youtube.com/embed/XC1gWCF5fk8"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                ></iframe>
            </div>
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-2">
                <Link class="btn btn-md btn-primary" :href="route('campaign.settings')">
                    {{ $t('helpPage.btns.campaignSettings') }}
                </Link>
                <button class="btn btn-md btn-outline-primary" @click="isShowedModal = true">
                    {{ $t('helpPage.btns.settingsRequest') }}
                </button>
            </div>
        </div>

        <modal :closeable="true"
               :show="isShowedModal"
               max-width="lg"
               @close="isShowedModal = false"
        >
            <div class="text-center">
                <div class="lg:py-8 py-4 lg:px-16 px-8">
                    <p class="mb-4 font-bold text-lg">{{ $t('helpPage.settingsRequest.header') }}</p>
                    <p class="mb-8 text-sm">
                        {{ $t('helpPage.settingsRequest.infoText') }}
                    </p>
                    <form @submit.prevent="onSendRequest">
                        <div class="form-field mb-4">
                            <text-input :placeholder="$t('helpPage.settingsRequest.form.name')"
                                        v-model="form.name"
                                        required
                            />
                        </div>
                        <div class="form-field mb-8">
                            <text-input :placeholder="$t('helpPage.settingsRequest.form.phone')"
                                        v-model="form.phone"
                                        mask="tel"
                                        required
                            />
                        </div>
                        <div class="form-field mb-8">
                            <select class="select-control w-full" required v-model="form.campaign_id">
                                <option disabled selected value="">{{
                                        $t('helpPage.settingsRequest.form.campaign')
                                    }}
                                </option>
                                <option v-for="campaign in campaigns"
                                        :key="campaign.id"
                                        :value="campaign.id"
                                >{{ campaign.name }}
                                </option>
                            </select>
                        </div>
                        <validation-errors/>
                        <div class="grid grid-cols-1 gap-4">
                            <button class="btn btn-primary w-full">
                                {{ $t('helpPage.settingsRequest.form.submit') }}
                            </button>
                            <button class="btn btn-outline-primary w-full"
                                    type="button"
                                    @click.prevent="isShowedModal = false"
                            >{{ $t('helpPage.settingsRequest.form.close') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </modal>
    </div>
</template>

<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import TextInput from "@/Components/Forms/TextInput.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import {onMounted, ref} from "vue";
import Modal from "@/Components/Modal/Modal.vue";

const isShowedModal = ref(false);

const form = useForm({
    name: '',
    phone: '+7',
    campaign_id: '',
});

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
    campaigns: {
        type: Array,
        required: true,
    },
});

onMounted(() => {
    form.name = props.auth?.user?.name ?? '';
    form.phone = props.auth?.user?.phone ?? '+7';
});

function onSendRequest() {
    form.post(route('settingsRequest.store'), {
        onFinish: () => {
            isShowedModal.value = false;
            form.reset();
        }
    });
}
</script>

<script>
import Layout from '@/Layouts/Authenticated.vue'

export default {layout: Layout}
</script>


<style scoped lang="scss">
.dashboard-content {
    min-height: calc(100vh - 7.6rem);
}

.guide-video {
    aspect-ratio: 16/9;

    @supports not (aspect-ratio: 16 / 9) {
        min-height: 350px;
    }
}
</style>
