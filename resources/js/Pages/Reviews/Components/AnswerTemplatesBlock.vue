<template>
    <div class="relative min-h-[128px] flex flex-col gap-1">
        <loading-block spinner-dark class="bg-gray-500/10" paddings v-if="templates === null"/>
        <div class="overflow-y-auto overflow-x-hidden flex-grow">
            <div class="flex flex-col items-stretch gap-y-1.5" v-if="!isEmpty2(templates)">
                <div
                    v-for="(template, i) in templates"
                    :key="template.id"
                    class="border border-gray-200 text-gray-400 rounded-lg text-sm p-2 cursor-pointer"
                    @click="selectTemplate(template)"
                >
                    Шаблон {{ i + 1 }}: {{ template.name }}
                </div>
            </div>
            <div v-else class="text-center text-gray-500 text-sm">
                *Шаблоны отсутствуют*
            </div>
        </div>
        <div class="flex-grow"></div>
        <div class="flex flex-row gap-x-2 justify-end">
            <img
                alt="refresh"
                src="/icons/refresh.svg"
                class="w-3 cursor-pointer"
                @click="refresh"
            />
            <span class="link text-sm cursor-pointer" @click="isSettingsModalShow = true">
                Настроить шаблоны
            </span>
        </div>

        <modal
            v-model:show="isSettingsModalShow"
            closeable
            max-width="2xl"
            @close="isSettingsModalShow = false"
        >
            <div class="p-4">
                <h2 class="text-lg font-bold">Настройка шаблонов ответов</h2>
                <div class="relative mt-4 min-h-[128px]">
                    <loading-block spinner-dark class="bg-gray-500/10" paddings v-if="templates === null"/>

                    <div v-if="!isEmpty2(templates)" class="flex flex-col">
                        <div
                            v-for="template in templates"
                            :key="template.id"
                            class="border-b py-2"
                        >
                            <template v-if="editingTemplateId !== template.id">
                                <div class="text-gray-500 flex gap-2">
                                    <span>{{ template.name }}</span>
                                    <span class="flex-grow"></span>
                                    <img
                                        class="cursor-pointer"
                                        alt="delete"
                                        src="/icons/delete.svg"
                                        @click="onTemplateDelete(template)"
                                    >
                                    <img
                                        class="cursor-pointer"
                                        alt="update"
                                        src="/icons/edit.svg"
                                        @click="startTemplateEdit(template)"
                                    >
                                </div>
                                <p class="border border-gray-300 rounded-lg p-3 whitespace-pre-line">
                                    {{ template.text }}
                                </p>
                            </template>
                            <form v-else class="space-y-1" @submit.prevent="sendUpdateTemplate">
                                <text-input
                                    v-model="templateEditForm.name"
                                    :error="templateEditForm.errors.name"
                                    placeholder="Название шаблона"
                                />
                                <input-text-area
                                    v-model="templateEditForm.text"
                                    :error="templateEditForm.errors.text"
                                    placeholder="Текст шаблона"
                                    class="form-control h-32"
                                />
                                <button type="submit" class="btn btn-md btn-primary">Сохранить</button>
                                <button
                                    type="submit"
                                    class="btn btn-md btn-outline-base ml-2"
                                    @click="editingTemplateId = null"
                                >Отменить</button>
                            </form>
                        </div>
                    </div>
                    <div v-else class="text-center text-gray-500 text-sm">
                        *Шаблоны отсутствуют*
                        <hr class="my-2"/>
                    </div>
                </div>

                <form @submit.prevent="sendNewTemplate" class="flex flex-col gap-2 mt-4">
                    <h3 class="font-bold">Новый шаблон</h3>
                    <text-input
                        v-model="newTemplateForm.name"
                        :error="newTemplateForm.errors.name"
                        placeholder="Название шаблона"
                    />
                    <input-text-area
                        v-model="newTemplateForm.text"
                        :error="newTemplateForm.errors.text"
                        placeholder="Текст шаблона"
                        class="form-control h-32"
                    />
                    <button type="submit" class="btn btn-md btn-primary self-start">Создать</button>

                    <p class="mt-4 text-sm">
                        Подстановка значений:<br>
                        <code>{org}</code> - название филиала<br>
                        <code>{name}</code> - имя клиента<br>
                    </p>
                </form>
            </div>
        </modal>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {isEmpty2} from "@/utils";
import LoadingBlock from "@/Components/LoadingBlock.vue";
import Modal from "@/Components/Modal/Modal.vue";
import {router, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputTextArea from "@/Pages/Reviews/Components/InputTextArea.vue";

const props = defineProps({
    projectId: {
        type: Number,
        required: true,
    },
    vars: {
        type: Object,
        required: false,
        default: {},
    },
});

const emit = defineEmits(['select']);

const templates = ref(null);
const isSettingsModalShow = ref(false);
const newTemplateForm = useForm({
    name: '',
    text: '',
});

function selectTemplate(template) {
    let text = template.text;

    for (const key of Object.keys(props.vars)) {
        text = text.replaceAll(`{${key}}`, String(props.vars[key]));
    }

    emit('select', text);
}

onMounted(loadTemplates);

const editingTemplateId = ref();
const templateEditForm = useForm({
    name: '',
    text: '',
});
function startTemplateEdit(template) {
    editingTemplateId.value = template.id;
    templateEditForm.defaults({
        name: template.name,
        text: template.text,
    });
    templateEditForm.reset();
}

function sendUpdateTemplate() {
    if (editingTemplateId.value === null) {
        return
    }

    templateEditForm.put(makeRoute('update', editingTemplateId.value), makeRequestOptions(() => {
        editingTemplateId.value = null;
    }));
}

async function loadTemplates() {
    templates.value = (await axios.get(makeRoute('get'))).data;
}

function refresh() {
    templates.value = null;
    loadTemplates();
}

function makeRequestOptions(onSuccess = undefined) {
    return {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            loadTemplates();
            onSuccess ? onSuccess() : null;
        }
    }
}

function onTemplateDelete(template) {
    if (!confirm(`Вы действительно хотите удалить шаблон '${template.name}'?`)) {
        return;
    }

    router.delete(makeRoute('delete', template.id), makeRequestOptions());
}

function sendNewTemplate() {
    newTemplateForm.post(makeRoute('create'), makeRequestOptions(() => {
        newTemplateForm.reset();
    }));
}

function makeRoute(name, template = undefined) {
    return route(`reviews.private.answer-templates.${name}`, {
        campaign: props.projectId,
        reviewAnswerTemplate: template,
    });
}
</script>
