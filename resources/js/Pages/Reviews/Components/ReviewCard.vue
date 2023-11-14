<template>
    <div class="card p-4 rounded bg-gray-100 flex flex-col font-medium">
        <div class="flex justify-start items-center gap-x-4">
            <div class="flex gap-x-3 mb-3 font-bold items-center">
                <span>{{ formatDate(review.created_at) }}</span>
                <span>{{ review.review_form.name }}</span>
<!--                <a-->
<!--                    v-if="reviewLink"-->
<!--                    class="text-gray-400 cursor-pointer"-->
<!--                    :href="reviewLink"-->
<!--                    target="_blank"-->
<!--                >-->
                    <img alt="source-logo" class="h-6" :src="reviewSourceIcons[review.source]"/>
<!--                </a>-->
            </div>

            <div class="flex-grow"></div>

            <div class="flex gap-x-1">
                <tippy
                    content="Отмеченные отзывы будут выводиться под формой"
                ><star
                    not-fill-class="fill-gray-100"
                    fill-class="fill-gray-300"
                    :filled="review.status === 'accepted'"
                    @click="review.status === 'accepted' ? declineReview() : acceptReview()"
                    class="cursor-pointer stroke-1 stroke-gray-500/50"
                /></tippy>

                <img
                    v-if="review.source === 'daily-grow'"
                    src="/icons/delete.svg"
                    class="cursor-pointer mx-1"
                    @click="removeReview"
                    alt="Удалить"
                />
            </div>

            <stars-input :readonly="true" v-model="review.stars"/>
        </div>

        <div class="flex gap-x-2 font-bold items-center">
            <span>{{ review.name }}</span>
            <span v-if="review.contact" class="text-sm">{{ review.contact }}</span>
        </div>

        <p class="mb-2 break-normal whitespace-pre-line">{{ review.comment }}</p>

        <div
            v-if="!isEmpty2(review.answer)"
            class="rounded-lg ml-16 px-3 py-2"
            :class="`${isEmpty2(review.answer.author_id) ? 'bg-gray-200' : 'bg-green-100'}`"
        >
            <div class="flex gap-2 items-center">
                <h4 class="font-bold">Ответ от имени компании</h4>
                <span class="text-gray-600 text-sm">
                    <template v-if="isEmpty2(review.answer.author_id)">
                        {{ dateToUserTzString(review.answer.created_at) }}
                    </template>
                    <template v-else-if="!isEmpty2(review.answer.published_at)">
                        Ответ опубликован {{ dateToUserTzString(review.answer.published_at) }}
                    </template>
                    <template v-else>Ответ ещё не опубликован</template>
                </span>
                <span class="flex-grow"></span>
                <template v-if="review.answers_allowed">
                    <img
                        alt="update"
                        src="/icons/edit.svg"
                        class="w-4 cursor-pointer"
                        @click="showUpdateAnswer"
                    >
                    <img
                        alt="update"
                        src="/icons/delete.svg"
                        class="w-3 cursor-pointer"
                        @click="onDeleteAnswer"
                    >
                </template>
            </div>

            <p class="mt-2 whitespace-pre-line">{{ review.answer.text }}</p>
        </div>

        <div class="mt-2 flex gap-2 lg:flex-row flex-col">
            <div class="border rounded-lg flex items-center flex-grow gap-x-4">
                <button
                    class="border-r rounded-lg py-1.5 px-6 text-sm whitespace-nowrap"
                    @click="addTag"
                >Добавить тег</button>
                <span
                    class="text-sm text-gray-600 hover:line-through cursor-pointer"
                    v-for="tag in review.tags"
                    @click="removeTag(tag.tag)"
                >#{{ tag.tag }}</span>
            </div>
            <button
                v-if="false"
                @click="onSendReport"
                class="border rounded-lg py-1.5 px-6 text-sm"
            >Пожаловаться</button>
            <button
                v-if="review.answers_allowed && isEmpty2(review.answer)"
                @click="showAnswerForm = !showAnswerForm"
                class="border-r rounded-lg py-1.5 px-6 text-sm text-white bg-primary"
            >Ответить</button>
            <tippy
                v-if="review.source === 'double-gis' && !review.review_form.widget_2gis_access"
                content="Для доступа к ответам в 2GIS предоставьте доступ к организации на сервисный аккаунт dailygrow@yandex.ru и нажмите кнопку проверки доступа в настройках филиала"
            >
                <button
                    class="border-r rounded-lg py-1.5 px-6 text-sm text-white bg-primary/50 cursor-help"
                    disabled
                    type="button"
                >Ответить</button>
            </tippy>
        </div>
    </div>

    <form v-if="review.answers_allowed && showAnswerForm" class="card mt-4 px-3 md:ml-16" @submit.prevent="onSendAnswer">
        <h3 class="mb-2 font-bold">Ответить на отзыв</h3>
        <div class="flex md:flex-row flex-col gap-4 md:mb-0 mb-4">
            <div class="flex-grow">
                <textarea
                    :maxlength="answerMaxLen"
                    v-model="answerForm.text"
                    class="w-full rounded-lg border-gray-200 h-40"
                ></textarea>
                <div class="flex items-start text-sm text-gray-400">
                    <p class="text-red-500 text-sm mb-2" v-if="answerForm.errors.text">{{ answerForm.errors.text }}</p>
                    <span class="flex-grow"></span>
                    <span>{{ answerForm.text.length }} из {{ answerMaxLen }} символов</span>
                </div>
            </div>
            <answer-templates-block
                class="2xl:w-60 lg:w-48 md:w-32 max-h-48"
                :project-id="campaign.id"
                :review-form-id="review.review_form_id"
                @select="answerForm.text = $event"
                :vars="{
                    name: review.name.split(' ')[0] ?? undefined,
                    org: review.review_form.name,
                }"
            />
        </div>
        <div class="flex gap-x-2">
            <button
                class="btn btn-md btn-primary"
                type="submit"
            >Отправить</button>
            <button
                class="btn btn-md btn-outline-base"
                @click="showAnswerForm = false"
                type="button"
            >Отменить</button>
        </div>
    </form>

    <form v-if="showAnswerUpdateForm" class="card mt-4 px-3 ml-16" @submit.prevent="onUpdateAnswer">
        <h3 class="mb-2 font-bold">Редактировать ответ на отзыв</h3>
        <textarea
            :maxlength="answerMaxLen"
            v-model="answerUpdateForm.text"
            class="w-full rounded-lg border-gray-200"
        ></textarea>
        <div class="flex items-start justify-end text-sm text-gray-400">
            <span>{{ answerUpdateForm.text.length }} из {{ answerMaxLen }} символов</span>
        </div>
        <p class="text-red-500 text-sm mb-2" v-if="answerUpdateForm.errors.text">{{ answerUpdateForm.errors.text }}</p>
        <div class="flex gap-x-2">
            <button
                class="btn btn-md btn-primary"
                type="submit"
            >Изменить</button>
            <button
                class="btn btn-md btn-outline-base"
                @click="showAnswerUpdateForm = false"
                type="button"
            >Отменить</button>
        </div>
    </form>
</template>

<script setup>
import {dateToUserTzString, isEmpty2} from "@/utils";
import {computed, ref} from "vue";
import StarsInput from "@/Pages/Reviews/Components/StarsInput.vue";
import {router, useForm} from "@inertiajs/vue3";
import Tippy from "@/Components/Tippy.vue";
import Star from "@/Pages/Reviews/Components/Star.vue";
import AnswerTemplatesBlock from "@/Pages/Reviews/Components/AnswerTemplatesBlock.vue";

const props = defineProps({
    review: {
        type: Object,
        required: true,
    },
    campaign: {
        type: Object,
        required: true,
    },
});

function onSendReport() {
    alert('Сейчас недоступно...');
}

const showAnswerForm = ref(false);
const answerMaxLen = 2500;
const answerForm = useForm({
    text: '',
});

const showAnswerUpdateForm = ref(false);
const answerUpdateForm = useForm({
    text: '',
});

function onSendAnswer() {
    answerForm.post(route('reviews.private.reviews.send-answer', {
        campaign: props.campaign.id,
        review: props.review.id,
    }), {
        preserveScroll: true,
        onSuccess: () => {
            answerForm.reset();
            showAnswerForm.value = false;
        }
    });
}

function showUpdateAnswer() {
    answerUpdateForm.text = props.review.answer.text;
    showAnswerUpdateForm.value = true;
}

function onUpdateAnswer() {
    answerUpdateForm.put(route('reviews.private.reviews.update-answer', {
        campaign: props.campaign.id,
        review: props.review.id,
    }), {
        preserveScroll: true,
        onSuccess: () => {
            answerUpdateForm.reset();
            showAnswerUpdateForm.value = false;
        }
    });
}

function onDeleteAnswer() {
    if (!confirm('Вы действительно хотите удалить ответ на этот отзыв?')) {
        return;
    }

    router.delete(route('reviews.private.reviews.delete-answer', {
        campaign: props.campaign.id,
        review: props.review.id,
    }), {preserveScroll: true});
}

function addTag() {
    const tag = prompt('Введите тэг без #');
    if (isEmpty2(tag)) {
        return;
    }

    router.post(route('reviews.private.reviews.add-tag', [
        props.campaign.id,
        props.review.id,
    ]), {
        tag
    }, {
        preserveScroll: true,
    });
}

function removeTag(tag) {
    if (!confirm(`Вы действительно хотите удалить тег #${tag} с этого отзыва?`)) {
        return;
    }

    router.post(route('reviews.private.reviews.remove-tag', [
        props.campaign.id,
        props.review.id,
    ]), {
        tag
    }, {
        preserveScroll: true,
    });
}

let showCommentInput = ref(false);
const commentForm = useForm({
    comment: '',
    review_id: props.review.id,
    review_form_id: props.review.review_form_id,
});

const reviewLink = computed(() => {
    switch (props.review.source) {
        case 'daily-grow':
            return null;
        case 'double-gis':
            return null;
        case 'yandex':
            return `https://yandex.ru/web-maps/org/${props.review.source_id}/reviews?reviews[publicId]=${props.review.external_id}`;
    }
});

function formatDate(date) {
    return dateToUserTzString(
        date,
        {day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'}
    );
}

const reviewSourceIcons = {
    'yandex': '/icons/reviews/map_yandex.svg',
    'double-gis': '/icons/reviews/map_2gis.svg',
    'daily-grow': '/mstile-70x70.png',
};

function reviewSourceFormat(source) {
    return {
        'yandex': 'Яндекс.Карты',
        'double-gis': '2GIS',
        'daily-grow': 'Daily Grow',
    }[source] ?? source;
}

function submitComment() {
    commentForm.post(route('reviews.private.add-comment', {campaign: props.campaign}), {
        preserveScroll: true,
        onSuccess: () => {
            showCommentInput.value = false;
            commentForm.reset();
        },
        onFinish: () => {
            showCommentInput.value = false;
            commentForm.reset();
        },
    });
}

function removeReview() {
    if (!confirm('Вы действительно хотите удалить данный отзыв?')) {
        return;
    }

    router.delete(route('reviews.private.delete', {
        review: props.review.id,
        campaign: props.campaign,
    }));
}

function acceptReview() {
    updateStatus('accepted');
}

function declineReview() {
    updateStatus('declined');
}

function updateStatus(status) {
    router.put(route('reviews.private.update-status', {
        review: props.review.id,
        campaign: props.campaign,
    }), {
        status,
    }, {
        preserveScroll: true,
    });
}
</script>
