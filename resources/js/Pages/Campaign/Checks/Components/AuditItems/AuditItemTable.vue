<template>
    <table v-if="item?.details?.items?.length && item.details?.type !== 'debugdata'"
           class="seo-audit-card__table mb-4">
        <thead>
        <tr>
            <th v-for="head in item['details']['headings']">{{ getHeaderText(head) }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="i in item['details']['items']">
            <td v-for="head in item['details']['headings']">
                <template v-if="(head?.valueType ?? head?.itemType) === 'url'">
                    <a :href="i[head['key']]"
                       :title="i[head['key']]['url']"
                       target="_blank"
                       rel="noopener">
                        {{ formatUrl(i[head['key']]) }}
                    </a>
                </template>
                <template v-else-if="(head?.valueType ?? head?.itemType) === 'source-location'">
                    <div :title="i[head['key']]['url']">
                        {{ formatUrl(i[head['key']]['url']) }}
                    </div>
                </template>
                <template v-else-if="(head?.valueType ?? head?.itemType) === 'bytes'">
                    {{ (i[head['key']] / 1024).toFixed(1) }} Кбайт
                </template>
                <template v-else-if="(head?.valueType ?? head?.itemType) === 'ms'">
                    {{ formatSmTime(i[head['key']]) }}
                </template>
                <template v-else-if="(head?.valueType ?? head?.itemType) === 'node' && i[head['key']]">
                    <div>{{ i[head['key']]['nodeLabel'] ?? '' }}</div>
                    <div class="seo-audit-card__selector">{{ i[head['key']]['selector'] }}</div>
                    <div class="seo-audit-card__snippet">{{ i[head['key']]['snippet'] }}</div>
                </template>
                <template v-else>
<!--                    <div style="color: red;">{{ head }} vl</div>-->
                    {{ i[head['key']] }}
                </template>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script setup>
import {r} from "@/utils"

const props = defineProps({
    item: {
        type: Object,
        required: true,
    }
});

const formatSmTime = (duration) => {
    if (duration > 2 * 1000) {
        const milliseconds = Math.floor(duration % 1000);
        const seconds = Math.floor(duration / 1000);

        if(milliseconds === 0) {
            return seconds + ' сек';
        }

        return `${seconds} сек ${milliseconds} мс`;
    }

    return r(duration) + ' мс';
}

const formatUrl = (url) => {
    const withoutDomain = url.replace(/^http[s]?:\/\/[^\/]+/i, '');

    if (withoutDomain.length > 45) {
        return withoutDomain.substring(0, 45) + '...';
    }

    return withoutDomain;
};

function getHeaderText(head) {
    let res = head['text'] ?? head['label'];

    switch (head.valueType) {
        case 'timespanMs':
            res += ', мс';
            break;
    }

    return res;
}

</script>

<style scoped lang="scss">
.seo-audit-card {
    &__head {
        display: flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        border-top: 1px solid #ececec;
        border-bottom: 1px solid #ececec;

        &:hover {
            background: #f3f3f3;
        }
    }

    &__title {
        &_sm {
            font-size: 13px;
            color: #d50225;
        }
    }

    &__time {
        justify-self: flex-end;
    }

    &__content {
        padding: 12px 24px;
        color: #252733;

        a {
            @apply text-primary;
        }
    }

    &__status {
        width: 8px;
        height: 8px;
        margin-right: 8px;
        border-radius: 50%;
        background-color: #b7b7b7;

        &_success {
            background-color: #76D276;
        }

        &_warning {
            background-color: #ffa400;
        }

        &_error {
            background-color: #FE7964;
        }
    }

    &__table {
        width: 100%;
        border: 1px solid #ececec;
        text-align: left;

        th, td {
            padding: 8px;
            word-break: normal;
        }

        tbody > tr:nth-child(odd) {
            background-color: hsla(210, 17%, 77%, 0.1);
        }
    }

    &__snippet {
        color: #0938C2;
        line-height: 20px;
        font-size: 12px;
    }

    &__selector {
        font-size: 12px;
    }
}
</style>
