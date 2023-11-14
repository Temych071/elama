<template>
    <div class="p-3 w-full">

        <div class="notifs-scroll-container">

            <div v-if="notifications?.length > 0">
                <div v-if="!(newNotifications.length === 0)" class="p-2 w-full flex items-center justify-items-start flex-fill flex-row flex-nowrap">
                    <div class="px-4 text-xs leading-5 flex-shrink-0 mr-5 text-error text-opacity-40">
                        {{ $t('header.notifications.new') }}
                    </div>
                    <div class="notifications-divider"></div>
                </div>

                <div v-if="!(newNotifications.length === 0)" class="notifications-list">
                    <div class="notification" v-for="item of newNotifications">
                        <div class="ntf-header align-center">
                            <div class="type bg-error">{{ $t(item.data.type) }}</div>
                            <div class="time">{{ formatDate(item.data.date) }}</div>
                            <a class="ntf-campaign">{{ item.data.campaignName }}</a>
                        </div>
                        <div class="ntf-title">
                            {{ item.data.title }}
                        </div>
                        <div class="ntf-body" v-html="item.data.text"/>
                    </div>
                </div>

                <div class="notifications-divider mt-3 mb-2"></div>

                <div class="notifications-list old">
                    <div class="notification" v-for="item of readNotifications">
                        <div class="ntf-header">
                            <div class="type bg-error">{{ $t(item.data.type) }}</div>
                            <div class="time">{{ formatDate(item.data.date) }}</div>
                            <a class="ntf-campaign">{{ item.data.campaignName }}</a>
                        </div>
                        <div class="ntf-title">
                            {{ item.data.title }}
                        </div>
                        <div class="ntf-body" v-html="item.data.text"/>
                    </div>
                </div>
            </div>

            <div v-else>
                <div class="px-4 text-xs leading-5 flex-shrink-0 mr-5 text-error text-opacity-80">
                    {{ $t('Вы ещё не получали уведомления') }}
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import {dateToUserTzString} from '@/utils';

export default {
    name: "NotifsDropdown",
    computed: {
        newNotifications() {
            let listOfNew = [];
            for (const item of this.$page.props.notifications) {
                if (item.read_at === null) {
                    listOfNew.push(item);
                }
            }
            return listOfNew;
        },

        readNotifications() {
            let listOfRead = [];
            for (const item of this.$page.props.notifications) {
                if (item.read_at !== null) {
                    listOfRead.push(item);
                }
            }

            return listOfRead;
         }
    },
    methods: {
        formatDate(date) {
            return dateToUserTzString(date, {
                hourCycle: 'h24',
                day: 'numeric',
                month: 'long',
                hour: 'numeric',
                minute: 'numeric',
            });
        }
    },
    data() {
        return {
            notifications: this.$page.props.notifications,
        }
    },
}
</script>

<style scoped>

</style>
