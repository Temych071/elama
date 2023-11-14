<template>
    <div class="py-3 border-b">
        <div class="flex flex-row justify-start items-center"
             :class="{'cursor-pointer': check.failedObjects.length < 1}"
             @click.prevent="isDeployed = (!isDeployed || check.failedObjects.length)"
        >
            <check-status-icon :status="!check.failedObjects.length" class="mr-2"/>
            <span class="font-bold">{{ check.rule.title }}</span>
            <slider-deploy-button v-if="!check.failedObjects.length"
                                  v-model:is-deployed="isDeployed"
                                  class="flex-shrink-0"
            />
        </div>
        <div v-if="isDeployed" class="ml-8">
            <div v-if="!isEmpty(check.message)"
                 class="text-primary underline cursor-pointer"
                 @click="isShowedModal = !isShowedModal"
            >
                {{ check.message }}
            </div>
            <p class="mt-4" v-html="check.rule.desc"></p>
        </div>

        <modal v-if="!isEmpty(check.message) && check.failedObjects.length"
               :show="isShowedModal"
               max-width="max-w-5xl"
               :closeable="true"
               @close="isShowedModal = false"
        >
            <div class="md:py-6 md:px-16 p-6">
                <h3 class="text-lg font-bold text-center">{{ check.message }}</h3>
                <p class="text-left mb-4" v-html="check.rule.desc"></p>
                <div class="overflow-x-auto">
                    <table class="check-objects-table">
                        <thead>
                        <tr>
                            <th>Индекс</th>
                            <th>Название</th>
                            <th>Название кампании</th>
                            <th>Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="object in sortedObjects">
                            <td>{{ object.index }}</td>
                            <td>
                                <a class="cursor-pointer text-primary-dark underline" :href="object.url" target="_blank">
                                    {{ object.name }}
                                </a>
                            </td>
                            <td>
                                <a
                                    v-if="object.campaign"
                                    class="cursor-pointer text-primary-dark underline"
                                    :href="object.campaign?.url"
                                    target="_blank"
                                >
                                    {{object.campaign?.name ?? ''}}
                                </a>
                            </td>
                            <td>
                                <template v-if="!isEmpty(object.custom?.status)">
                                    <check-object-status-icon :status="object.custom.status"/>
                                </template>
                                <template>
                                    -
                                </template>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </modal>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import CheckStatusIcon from "@/Pages/Campaign/Checks/Components/CheckStatusIcon.vue";
import SliderDeployButton from "@/Pages/Campaign/Checks/Components/SliderDeployButton.vue";
import {isEmpty} from "@/utils";
import Modal from "@/Components/Modal/Modal.vue";
import CheckObjectStatusIcon from "@/Pages/Campaign/Checks/Components/CheckObjectStatusIcon.vue";

const props = defineProps({
    check: {
        type: Object,
        required: true,
    },
    groupName: {
        type: String,
        required: true,
    },
});

const isDeployed = ref(props.check.failedObjects.length);
const isShowedModal = ref(false);


const sortedObjects = computed(() => {
    const order = {on: 1, off: 2, draft: 3};
    return props.check.failedObjects.sort(({custom: a}, {custom: b}) => order[a.status] - order[b.status]);
});
</script>
