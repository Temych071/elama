<template>
    <div class="html-editor">
        <div class="w-full h-full" ref="editorElement"></div>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import Quill from "quill/dist/quill";
import {isEmpty} from "@/utils";
import 'quill/dist/quill.snow.css';

const props = defineProps({
    modelValue: {
        type: [String, Object, null],
        required: true,
    },
    options: {
        type: Object,
        required: false,
        default: {},
    },
    placeholder: {
        type: String,
        required: false,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const editorElement = ref(null);
const editor = ref();

const isDeltaEmpty = (delta) => {
    if (isEmpty(delta?.ops)) {
        return true;
    }

    if ((delta.ops?.length ?? 0) > 1) {
        return false;
    }

    let s = delta.ops[0]?.insert;

    return s.replaceAll(/\s/g, '') === '';
};

onMounted(() => {
    const quill = new Quill(editorElement.value, {
        theme: 'snow',
        modules: {
            toolbar:  [
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline'],
                [{ list: 'ordered' }, { list: 'bullet' }, { align: [] }],
                ['blockquote', 'code-block', 'link'],
                [{ color: [] }, 'clean'],
            ],
        },
        placeholder: props.placeholder,

        ...props.options,
    });

    quill.setContents(quill.clipboard.convert(props.modelValue), 'silent');

    quill.on('editor-change', () => {
        const delta = quill.getContents();
        if (isDeltaEmpty(delta)) {
            emit('update:modelValue', '');
        }
        emit('update:modelValue', quill.container.querySelector('.ql-editor').innerHTML);
    });

    editor.value = quill;
});

</script>
