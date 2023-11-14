<template>
    <article class="seo-audit-card">
        <div class="seo-audit-card__head" @click="isCollapsed = !isCollapsed">
            <div
                :class="{
                'seo-audit-card__status_success': item['score'] >= .9,
                'seo-audit-card__status_warning': item['score'] >= .5 && item['score'] < .9,
                'seo-audit-card__status_error': item['score'] !== null && item['score'] >= 0 && item['score'] < .5,
                }"
                class="seo-audit-card__status"
            ></div>
            <h4 class="seo-audit-card__title font-medium">{{ item['title'] }} <span v-if="item['displayValue']"
                                                                                    class="seo-audit-card__title_sm"> - {{
                    item['displayValue']
                }}</span></h4>
            <!--            <div class="seo-audit-card__time">{{ item['numericValue'] }}</div>-->
        </div>

        <div class="seo-audit-card__content" v-if="!isCollapsed">
            <div class="seo-audit-card__desc mb-6" v-html="formatDescription(item['description'])"></div>
            <div>
                <audit-item-table :item="item"/>
            </div>
        </div>
    </article>
</template>

<script setup>

import {ref} from "vue";
import Tippy from "@/Components/Tippy.vue";
import AuditItemTable from "@/Pages/Campaign/Checks/Components/AuditItems/AuditItemTable.vue";

const props = defineProps({
    item: Object,
});

const isCollapsed = ref(true);

const formatDescription = (desc) => {
    //check for links [text](url)
    let elements = desc.match(/\[.*?\)/g);
    if (elements != null && elements.length > 0) {
        for (let el of elements) {
            let txt = el.match(/\[(.*?)\]/)[1];
            let url = el.match(/\((.*?)\)/)[1];
            desc = desc.replace(el, '<a href="' + url + '" target="_blank">' + txt + '</a>')
        }
    }
    return desc;
};

const formatSmTime = (duration) => {
    if (duration > 2 * 1000) {
        const milliseconds = Math.floor(duration % 1000);
        const seconds = Math.floor(duration / 1000);

        if(milliseconds === 0) {
            return seconds + ' сек';
        }

        return `${seconds} сек ${milliseconds} мс`;
    }

    return duration + ' мс';
}

const formatUrl = (url) => {
    const withoutDomain = url.replace(/^http[s]?:\/\/[^\/]+/i, '');

    if (withoutDomain.length > 45) {
        return withoutDomain.substring(0, 45) + '...';
    }

    return withoutDomain;
};

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
