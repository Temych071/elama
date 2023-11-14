<template>
    <private-layout :loyalties="loyalties" :loyalty="loyalty">
        <div class="grid md:grid-cols-2 grid-cols-1">
            <div class="flex flex-col gap-y-4">
                <div class="form-field">
                    <label class="form-label">API-токен</label>
                    <text-input
                        readonly
                        :model-value="loyalty.api_token"
                        auto-select
                    />
                </div>
                <div class="form-field">
                    <label class="form-label">API URL</label>
                    <text-input
                        readonly
                        :model-value="route('loyalty.api.v1.root', loyalty.api_token)"
                        auto-select
                    />
                </div>
                <div class="form-field" v-if="freeCardsCount !== null">
                    <p>
                        <span class="font-bold">Свободных карт осталось: </span>
                        <span>{{ freeCardsCount }}</span>
                    </p>
                    <p>
                        <span class="font-bold">Последнее добавление карт: </span>
                        <span>{{ dateToUserTzString(lastCardSync) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </private-layout>
</template>

<script setup>
import PrivateLayout from "@/Pages/Loyalty/Layouts/PrivateLoyaltyLayout.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import {dateToUserTzString} from "@/utils";

const props = defineProps({
    loyalty: {
        type: Object,
        required: true,
    },
    loyalties: {
        type: Array,
        required: true,
    },
    freeCardsCount: {
        type: Number,
        required: false,
        default: null,
    },
    lastCardSync: {
        type: String,
        required: false,
        default: null,
    },
});
</script>
