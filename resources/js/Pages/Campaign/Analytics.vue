<template>
    <Head>
        <title>Аналитика</title>
    </Head>

    <div class="campaign-analytics">

        <div class="controls">
            <form-button
                class="btn btn-md btn-outline-primary float-right"

                method="get"
            >Обновить
            </form-button>

            <period-controls/>
        </div>

        <div class="card">
            <div class="sources-bar">
                <div class="source active">
                    <div class="title">ВСЕ</div>
                    <div class="money">123 123 ₽</div>
                </div>
                <div class="source">
                    <div class="title">Яндекс директ</div>
                    <div class="money">78 465 ₽</div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
import Layout from '@/Layouts/Authenticated.vue';
import {Head} from '@inertiajs/vue3';
import FormButton from "@/Components/Forms/FormButton.vue";

export default {
    layout: Layout,
    components: {
        FormButton,
        Head,
    }
}
</script>

<style scoped>

</style>
