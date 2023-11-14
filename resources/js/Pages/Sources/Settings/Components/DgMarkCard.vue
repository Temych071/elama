<template>
    <div class="card-md mt-4 p-8">
        <h2 class="title mb-2">Разметка обьявлений</h2>
        <p class="mb-3 text-sm">Для корректного сбора статистики по обьявлениям, они должны быть размечены специальной меткой.</p>
        <div class="form-field">
            <label class="form-label">Сгенерировання метка для текущего источника</label>
            <div class="flex flex-row">
                <input class="form-control flex-grow"
                       readonly
                       :value="markQueryParam"
                       @focusin="$event.target.setSelectionRange(0, $event.target.value.length)"
                />
                <button class="btn btn-outline-base h-full flex-shrink-0 ml-4"
                        @click="onCopy"
                >
                    <img alt="copy" src="/icons/copy.svg"/>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, nextTick} from "vue";

const props = defineProps({
    mark: {
        type: String,
        required: true,
    },
});

const markQueryParam = computed(() => `&dg=${props.mark}`);

function onCopy() {
    navigator.clipboard.writeText(markQueryParam.value);
    nextTick(() => alert('Daily Grow метка скопирована в буфер обмена.'));
}

</script>
