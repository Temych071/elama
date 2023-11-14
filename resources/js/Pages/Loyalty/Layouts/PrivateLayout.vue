<template>
    <authenticated>
        <p v-if="isEmpty2(loyalties)" class="py-2">
            Вы ещё не создавали программы лояльности.
        </p>
        <div class="flex flex-row flex-wrap">
            <div class="flex flex-row flex-wrap flex-grow">
                <nav-link
                    v-for="loyaltyItem in loyalties"
                    :key="loyaltyItem.id"
                    type="button"
                    class="btn btn-sm btn-primary mr-2 mb-2"
                    :class="{disabled: loyalty?.id === loyaltyItem.id}"
                    :href="route('loyalty.private.loyalty.show', {
                        campaign: loyaltyItem.project_id,
                        loyalty: loyaltyItem.id
                    })"
                >{{ loyaltyItem.name }}</nav-link>
                <button
                    type="button"
                    class="btn btn-sm btn-primary mr-2 mb-2 w-24"
                    @click="onCreateLoyalty"
                >+</button>
            </div>
            <button
                v-if="!isEmpty2(loyalty)"
                class="btn btn-md btn-outline-base"
                @click="onDeleteLoyalty"
            >Удалить</button>
        </div>

        <slot/>
    </authenticated>
</template>

<script setup>
import Authenticated from "@/Layouts/Authenticated.vue";
import {isEmpty2} from "@/utils";
import NavLink from "@/Components/NavLink.vue";
import {router, usePage} from "@inertiajs/vue3";

const props = defineProps({
    loyalties: {
        type: Array,
        required: true,
    },
    loyalty: {
        type: Object,
        required: false,
    },
});

const pageProps = usePage().props;

function onCreateLoyalty() {
    const name = prompt('Введите название программы лояльности', 'Новая программа лояльности');
    if (isEmpty2(name)) {
        return;
    }

    router.post(route('loyalty.private.loyalty.index', pageProps.page_project.id), {name});
}

function onDeleteLoyalty() {
    if (!confirm(`Вы действительно хотите удалить программу лояльности '${props.loyalty.name}'?`)) {
        return;
    }

    router.delete(route('loyalty.private.loyalty.remove', [props.loyalty.project_id, props.loyalty.id]));
}
</script>
