<template>
    <div>
        <div class="mb-6">
            <a href="https://imtera.notion.site/2fab15e7974241b58872b813b9cc1b7e" target="_blank" class="text-sm text-primary-light">Смотреть инструкцию по настройке</a>
        </div>

        <article class="mb-6">
            <h2 class="title-sm mb-3">1). Установите webhook в настройках лид форм</h2>
            <input :value="webhookUrl" readonly type="text" class="form-control">
        </article>

        <article class="mb-6">
            <h2 class="title-sm mb-3">2). Выберите группу</h2>
            <div class="grid grid-cols-5 gap-4 mb-2">
                <div class="col-span-2">ID группы</div>
                <div class="col-span-2">Строка подтверждения</div>
            </div>
            <div v-for="(group, index) in webhooks"
                 :key="`group-${index}`"
                 class="grid grid-cols-5 gap-4 mb-2">
                <div class="col-span-2">
                    <input v-model="group.group_id" type="text" class="form-control" required>
                </div>
                <div class="col-span-2">
                    <input v-model="group.code" type="text" class="form-control" required>
                </div>
                <div class="flex items-center" v-if="index > 0">
                    <button @click.prevent="removeWebhook(index)" type="button" class="p-0.5">
                        <img src="/icons/delete.svg" alt="delete"/>
                    </button>
                </div>
            </div>
            <div v-if="webhooks.length < 3">
                <a href="#" @click.prevent="addWebhook" class="text-sm">+ Добавить поле</a>
            </div>
        </article>

        <article class="mb-6">
            <h2 class="title-sm mb-4">3). Учитывать показатели в статистике конверсий:</h2>
            <Checkbox v-model:checked="leadsMessagesProxy">Сообщения сообщества</Checkbox>
            <Checkbox v-model:checked="leadsFormsProxy">Отправка лид-формы</Checkbox>
        </article>
    </div>
</template>

<script setup>
import {computed, ref, watch} from "vue";
import Checkbox from "@/Components/Checkbox.vue";

const EMPTY_WEBHOOK = {group_id: '', code: ''};

const props = defineProps({
    campaigns: Array,
    sourceKey: String,
    modelValue: Array,
    leadsMessages: { required: true, type: Boolean },
    leadsForms: { required: true, type: Boolean },
});

const emit = defineEmits(['update:modelValue', 'update:leadsMessages', 'update:leadsForms']);

const webhooks = ref(props.modelValue ? [...props.modelValue] : [{...EMPTY_WEBHOOK}]);

const addWebhook = () => {
    webhooks.value.push({...EMPTY_WEBHOOK});
};

const removeWebhook = (index) => {
    webhooks.value.splice(index, 1);
};

const webhookUrl = computed(() => route('source.vk.webhook', {key: props.sourceKey}));

watch(
    () => webhooks,
    (newValue, oldValue) => {
        emit('update:modelValue', [...webhooks.value])
    },
    { deep: true }
)

const leadsFormsProxy = computed({
    get: () => props.leadsForms,
    set: (v) => emit('update:leadsForms', v),
});

const leadsMessagesProxy = computed({
    get: () => props.leadsMessages,
    set: (v) => emit('update:leadsMessages', v),
});

</script>
