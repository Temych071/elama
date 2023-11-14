<template>
    <div class="planfact-paginator flex flex-row flex-nowrap justify-center items-center">
        <div class="planfact-paginator-control" @click="onChangePage(false)">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12.8416 6.175L9.02489 10L12.8416 13.825L11.6666 15L6.66655 10L11.6666 5L12.8416 6.175Z"
                    :fill="getControlColor(curPage > 1)"
                />
            </svg>
        </div>
        <div class="planfact-paginator-indicator text-black">
            {{ curPage }} {{ $t('campaigns.planfact.block.paginator-of') }} {{ maxPage }}
        </div>
        <div class="planfact-paginator-control" @click="onChangePage(true)">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.15845 13.825L10.9751 10L7.15845 6.175L8.33345 5L13.3334 10L8.33345 15L7.15845 13.825Z"
                    :fill="getControlColor(curPage < maxPage)"
                />
            </svg>
        </div>
    </div>
</template>

<script>
import {clamp} from '@/utils';

export default {
    name: "PlanFactPaginator",
    emits: ['update:curPage'],
    props: {
        curPage: {
            type: Number,
        },
        maxPage: {
            type: Number,
            required: true,
        },
    },
    methods: {
        onChangePage(forward /*bool*/) {
            let newPage = this.curPage + (forward ? 1 : -1);
            newPage = clamp(newPage, 1, this.maxPage);
            this.$emit('update:curPage', newPage);
        },

        getControlColor(state) {
            return state ? '#657188' : '#BEC1C8';
        },
    },
}
</script>

<style scoped lang="scss">
.planfact-paginator-control {
    cursor: pointer;
}

.planfact-paginator-indicator {
    text-align: center;
    font-weight: normal;
    font-size: 9.92px;
    line-height: 12.45px;
}
</style>
