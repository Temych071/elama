<template>
    <div class="admin-settings-requests">
        <div>
            <div class="grid grid-cols-6 gap-x-8 items-center text-sm font-black uppercase py-4 px-8">
                <div>Проект</div>
                <div>Телефон</div>
                <div>Имя</div>
                <div>Пользователь</div>
                <div>Создано</div>
                <div></div>
            </div>
            <div class="campaigns-table-items">
                <article class="px-8 py-4 mb-6 text-sm border-none card" v-for="request of requests">
                    <div class="grid grid-rows-1 grid-cols-6 gap-x-8 items-center">
                        <div class="">
                            <Link class="text-primary-dark"
                                  v-if="request.campaign"
                                  :href="route('campaign.browse', request.campaign.id)"
                            >
                                {{ request.campaign.name }} ({{ request.campaign.id }})
                            </Link>
                            <span v-else class="text-gray-500">Удалён</span>
                        </div>
                        <div>+{{ request.phone }}</div>
                        <div>{{ request.name }}</div>
                        <div>
                            <span v-if="request.user">{{ request.user.name }} ({{ request.user.id }})</span>
                            <span v-else class="text-gray-500">Удалён</span>
                        </div>
                        <div>{{ dateFormat(request.created_at) }}</div>
                        <div>
                            <form-button :data="{request_id: request.id}"
                                         confirm="Вы действительно хотите удалить эту заявку?"
                                         method="delete"
                                         :action="route('admin.settingsRequests.delete')"
                                         class="btn btn-sm btn-error-light"
                            >Удалить
                            </form-button>
                        </div>
                    </div>
                </article>

            </div>
        </div>
    </div>
</template>

<script setup>
import {dateFormat} from "@/utils";
import {Link} from "@inertiajs/vue3";
import FormButton from "@/Components/Forms/FormButton.vue";

const props = defineProps({
    requests: {
        type: Array,
        required: true,
    }
});
</script>

<script>
import layout from "@/Layouts/Admin.vue";

export default {layout};
</script>
