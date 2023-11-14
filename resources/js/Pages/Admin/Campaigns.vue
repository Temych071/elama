<template>
    <div class="admin-campaigns">
        <div class="controls">
            <div class="ctrl-sort">
                <select v-model="selectedUser">
                    <option :value="null">Все</option>
                    <option
                        v-for="user of users"
                        :value="user"
                    >
                        {{ user.name }}
                    </option>
                </select>
            </div>
            <form-search class="ctrl-search"/>
        </div>

        <div class="campaigns-table">
            <div class="campaigns-table-header">
                <div>Проект</div>
                <div class="place-self-center">Аккаунты</div>
                <div>Действия</div>
            </div>
            <div class="campaigns-table-items">
                <template v-for="campaign of campaignsList">
                    <CampaignListItem :campaign="campaign" />
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/Layouts/Admin.vue';
import TextInput from "@/Components/Forms/TextInput.vue";
import FormSelect from "@/Components/Forms/FormSelect.vue";
import FormSearch from "@/Components/Forms/FormSearch.vue";
import CampaignListItem from "@/Pages/Admin/Components/CampaignListItem.vue";

export default {
    components: {CampaignListItem, FormSearch, FormSelect, TextInput},
    layout: Layout,
    props: ['campaigns', 'users'],
    data() {
        return {
            orderOptions: {
                balance: 'По балансу',
                name: 'По названию',
                index: 'По индексу',
            },
            selectedUser: null,
        };
    },
    computed: {
        campaignsList() {
            if(this.selectedUser) {
                return this.campaigns.filter(campaign => {
                    return campaign.users.some(user => +user.id === +this.selectedUser.id);
                })
            }

            return this.campaigns;
        },
    },
}
</script>
