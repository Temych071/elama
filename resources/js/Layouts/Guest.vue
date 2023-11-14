<template>
    <div class="min-h-screen flex align-middle bg-opacity-50 bg-sidebar flex-col">
        <div class="modal">
            <slot/>
        </div>
        <div class="text-center">
            <a href="https://dailygrow.ru/privacy_policy.pdf/"
               target="_blank"
               class="font-normal text-right text-black align-text-top text-xs mr-4">Политика конфиденциальности</a>

            <a href="https://dailygrow.ru/personal_data.pdf/"
               target="_blank"
               class="font-normal text-right text-black align-text-top text-xs mr-4">Согласие на обработку персональных
                данных</a>

            <a href="https://dailygrow.ru/oferta.pdf/"
               target="_blank"
               class="font-normal text-right text-black align-text-top text-xs">Лицензионное соглашение</a>
        </div>
    </div>
</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue'
import {Link} from '@inertiajs/vue3';

export default {
    components: {
        BreezeApplicationLogo,
        Link,
    }
}
</script>
