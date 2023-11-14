<template>
    <div v-if="item?.checkName === 'imageCount'">
        <image-count :status="status" :item="item"/>
    </div>

    <div v-else class="flex items-center">
        <check-status-icon :status="status" class="mr-2"/>
        <span
            class="font-bold"
            :class="{
                'text-success': item.status === 'fast',
                'text-[#fb923c]': item.status === 'middle',
                'text-danger': item.status === 'slow',
            }"
        >
            {{ title }}
        </span>
        <slider-deploy-button v-if="!isEmpty2(description) || !isEmpty2(item.image)" v-model:is-deployed="isDeployed"/>
    </div>
    <div v-if="isDeployed" class="ml-8">
        <img v-if="item.image" :src="item.image" alt="image" />

        <template v-if="!isEmpty2(description)">
            <template v-if="Array.isArray(description)">
                <div v-for="desItem of description">
                    <a :href="findLink(desItem)" target="_blank">{{desItem}}</a>
                </div>
            </template>
            <div v-else>
                <p v-html="description"></p>
            </div>
        </template>
        <audit-item-table :item="item"/>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import CheckStatusIcon from "@/Pages/Campaign/Checks/Components/CheckStatusIcon.vue";
import SliderDeployButton from "@/Pages/Campaign/Checks/Components/SliderDeployButton.vue";
import AuditItemTable from "@/Pages/Campaign/Checks/Components/AuditItems/AuditItemTable.vue";
import ImageCount from "@/Pages/Campaign/Checks/Components/AuditItems/ImageCount.vue";
import {isEmpty2} from "@/utils";
import {useI18n} from "vue-i18n";

const props = defineProps({
    item: {type: Object, required: true},
    status: {type: Boolean, required: true},
});

const isDeployed = ref(false);
const i18n = useI18n();

const findLink = (item) => {
    const urlRegex = /(https?:\/\/\S+)/g;
    let url = '#';
    item.replace(urlRegex, (data) => {
        url = data;
        return url;
    });
    if (url.charAt(url.length - 1) === '.') {
        url = url.slice(0, -1);
    }
    return url;
}

const title = computed(() => {
    return i18n.t(`seo-audit.${props.item.__key}.${props.item.score ? 'success' : 'failed'}.title`, props.item, String(props.item.title));
});

const description = computed(() => {
    if (typeof props.item.description === 'string' || isEmpty2(props.item.description)) {
        let res = i18n.t(`seo-audit.${props.item.__key}.${props.item.score ? 'success' : 'failed'}.desc`, props.item, String(props.item.description));
        res = res.replaceAll('\n', '<br>');
        return res.length ? res : null;
    }
    return props.item.description;
});

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
