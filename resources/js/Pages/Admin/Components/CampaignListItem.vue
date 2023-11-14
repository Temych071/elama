<template>
    <article class="campaigns-table-item">
        <div class="grid grid-cols-3 gap-x-8 items-center">
            <div class="grid grid-cols-2 grid-rows-2 items-center">
                <a class="row-span-2 text-primary"
                   :href="route('campaign.browse', campaign.id)"
                   target="_blank">
                    <span class="font-black">{{ campaign.name }}&nbsp;({{ campaign.id }})</span>
                </a>
            </div>
            <div class="place-self-center">
                {{ campaign.users.map(user => `${user.name}&nbsp;(${user.id})`).join(', ') }}
            </div>
            <div class="grid grid-rows-2 grid-cols-1 gap-y-2">
                <button class="btn btn-sm btn-outline-primary btn-block" @click.prevent="isOpen = !isOpen">
                    {{ isOpen ? 'Закрыть' : 'Раскрыть' }}
                </button>
            </div>
        </div>
        <div v-if="isOpen">
            <div v-for="source of campaign.sources">
                id:&nbsp;{{ source.id }}, type:&nbsp;{{ source.settings_type }}, settings:&nbsp;{{ source.settings_id }}
            </div>
        </div>
    </article>
</template>

<script setup>
import {ref} from "vue";

const props = defineProps({
    campaign: {
        type: Object,
        required: true,
    },
});

const isOpen = ref(false);
</script>

<style scoped>

</style>
