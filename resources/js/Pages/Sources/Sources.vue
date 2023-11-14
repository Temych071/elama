<template>
    <page-title :title="$t('sources.list.title') + ' - ' + campaign.name"/>

    <Authenticated>
        <section class="card-md card-dialog">
            <h1 class="card-title-d mb-5">{{ campaign.name }}</h1>

            <template v-if="connectedSources.length">
                <article>
                    <p v-if="hasInvalidTokens" class="text-error mb-4 text-sm">
                        Для одного или более источников требуется обновление доступа.
                    </p>

                    <div class="mb-2 text-sm text-gray-dark opacity-50">{{ $t('sources.list.connected') }}</div>

                    <div class="flex justify-between items-center mb-5" v-for="source in connectedSources">
                        <div class="flex items-center">
                            <SourceIcon :source-type="source.type" :size="21"/>
                            <span class="ml-3">{{ source.name }}</span>
                        </div>
                        <div class="flex items-center">
                            <button v-if="source.is_token_invalid"
                                    class="btn btn-sm btn-warning"
                                    @click.prevent="doConnect(source.type)"
                            >Обновить доступ
                            </button>
                            <Link v-else-if="sourceHasSettings(source.settings_type)"
                                  :href="route(`campaign.source.settings.${source.settings_type}.show`, campaign.id)"
                                  class="ml-2 hover:bg-gray-50 w-8 h-8 rounded-md flex items-center justify-center"
                            >
                                <Icon path="/icons/settings_outlined.svg" :size="17"/>
                            </Link>
                            <!--                            <button class="ml-2 hover:bg-gray-50 w-8 h-8 rounded-md">-->
                            <!--                                <Icon path="/icons/refresh.svg" :size="16"/>-->
                            <!--                            </button>-->
                            <form-button
                                class="ml-2 hover:bg-gray-50 w-8 h-8 rounded-md flex items-center justify-center"
                                method="DELETE"
                                :action="route(`campaign.source.delete`, {
                                    campaign: campaign.id,
                                    source_type: source.settings_type,
                                })"
                                :confirm="`Вы действительно хотите удалить источник ${source.name}?`"
                            >
                                <Icon path="/icons/delete.svg" :size="14" class="mb-0.5"/>
                            </form-button>
                        </div>
                    </div>
                </article>
                <div class="divider my-5"></div>
            </template>

            <article v-if="!isEmpty(availableSources)">

                <div class="mb-2 text-sm text-gray-dark opacity-50">{{ $t('sources.list.notConnected') }}</div>

                <div class="flex justify-between items-center mb-5" v-for="source in availableSources">
                    <div class="flex items-center">
                        <SourceIcon :source-type="source.type" :size="21"/>
                        <span class="ml-3">{{ source.name }}</span>
                    </div>
                    <div>
                        <button @click.prevent="doConnect(source.type)"
                                class="btn btn-sm btn-outline-primary"
                        >{{ $t('sources.list.connect') }}
                        </button>
                    </div>
                </div>
            </article>

        </section>

        <section class="card-md card-dialog mt-4">
            <h1 class="card-title-d mb-5">Настройка доступа</h1>

            <form @submit.prevent="onSubmitAddMember">
                <table class="members-table">
                    <thead>
                    <tr>
                        <th>Почта</th>
                        <th>Роль</th>
                        <th>Комментарий</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input placeholder="Почта"
                                   class="border-0 text-sm px-2"
                                   type="email"
                                   v-model.trim="addMemberForm.email"
                                   required
                        /></td>
                        <td>Участник</td>
                        <td><input placeholder="Комментарий"
                                   class="border-0 text-sm px-2"
                                   type="text"
                                   v-model.trim="addMemberForm.comment"
                                   required
                        /></td>
                        <td>
                            <div class="w-full flex flex-row items-center justify-center">
                                <button class="btn btn-primary btn-sm h-full">Добавить</button>
                            </div>
                        </td>
                    </tr>
                    <tr v-for="member in campaign_members">
                        <td>{{ member.email }}</td>
                        <td>{{ $t('campaigns.members.roles.' + member.role) }}</td>
                        <td>{{ member.comment ?? '-' }}</td>
                        <td>
                            <div class="w-full flex flex-row items-center justify-center">
                                <!--<form-button class="px-1"-->
                                <!--             :data="{email: member.email}"-->
                                <!--             method="DELETE">-->
                                <!--    <img alt="edit" src="/icons/edit.svg" />-->
                                <!--</form-button>-->
                                <form-button class="px-1"
                                             :confirm="`Вы действительно хотите удалить пользователя '${member.email}' из проекта?`"
                                             :data="{email: member.email}"
                                             method="DELETE"
                                             :action="route('campaign.members.delete', campaign.id)"
                                >
                                    <img alt="delete" src="/icons/delete.svg"/>
                                </form-button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>

        </section>
    </Authenticated>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated.vue";
import {Head, Link} from '@inertiajs/vue3';
import Label from "@/Components/Label.vue";
import Checkbox from "@/Components/Checkbox.vue";
import SourceIcon from "@/Pages/Sources/Components/SourceIcon.vue";
import Icon from "@/Components/Icon.vue";
import {useForm} from '@inertiajs/vue3';
import FormButton from "@/Components/Forms/FormButton.vue";
import {isEmpty} from '@/utils';
import PageTitle from "@/Components/PageTitle.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SearchableSelect from "@/Components/Forms/SearchableSelect.vue";

export default {
    components: {
        SearchableSelect,
        TextInput,
        PageTitle,
        FormButton,
        Icon,
        SourceIcon,
        Label,
        Checkbox,
        Authenticated,
        Head,
        Link,
    },
    props: {
        campaign: Object,
        campaign_sources: Array,
        sources: Array,
        roles: Array,
        campaign_members: Array,
    },
    computed: {
        availableSources() {
            return this.campaign_sources.length
                ? this.sources.filter(src => {
                    if (this.lockedSources[src.type])
                        return false;

                    return this.campaign_sources.find(conSrc =>
                        src.type === conSrc.settings_type
                    ) === undefined;
                })
                : this.sources;
        },
        connectedSources() {
            return this.campaign_sources.map(e => ({
                ...this.sources.find(item => item.type === e.settings_type),
                ...e,
            }));
        },
        lockedSources() {
            let lst = {};
            for (let locker of this.connectedSources)
                if (this.locksSources[locker.settings_type] instanceof Array)
                    for (let locked of this.locksSources[locker.settings_type])
                        lst[locked] = true;
            return lst;
        },
        hasInvalidTokens() {
            for (let i in this.connectedSources) {
                if (this.connectedSources[i].is_token_invalid) {
                    return true;
                }
            }
            return false;
        },
    },
    data() {
        return {
            locksSources: {
                'yandex-metrika': ['google-analytics'],
                'google-analytics': ['yandex-metrika'],
            },
            addMemberForm: this.$inertia.form({
                email: '',
                role: null,
                comment: '',
            }),
        };
    },
    methods: {
        isEmpty,

        doConnect(type) {
            useForm({
                campaign_id: this.campaign.id,
                type: type,
            }).post(this.route('source.add'));
        },

        onSubmitAddMember() {
            this.addMemberForm.post(route('campaign.members.add', this.campaign.id));
        },

        sourceHasSettings(sourceType) {
            return {
                avito: false,
            }[sourceType] ?? true;
        }
    },
}
</script>

<style scoped lang="scss">
.members-table {
    width: 100%;
    text-align: left;
    color: rgb(34, 51, 84);
    font-size: 0.875rem;
    line-height: 1.25rem;
    border-collapse: collapse;

    th, td {
        &, & > input {
            padding: 8px 8px;
        }
    }

    td:has(input) {
        padding: 0;
        margin: 0;
    }

    thead {
        border-bottom: 2px solid rgba(0, 0, 0, .1);
    }

    tbody tr {
        border-bottom: 1px solid rgba(0, 0, 0, .1);
    }
}
</style>
