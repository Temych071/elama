
<template>
       <a :href="route('auth.elama.redirect')">
        <div class="flex w-full items-center text-white font-bold text-sm rounded g-btn elama-btn">
            <div class="g-btn__icon bg-white rounded m-0.5 elama-btn__icon ">
                <img src="/icons/eLama.jpg" alt="">
            </div>
            <div class="mx-auto text-center">Войти через Elama</div>
        </div>
    </a>
</template>

<script setup>
const props = defineProps({ 
    title: { type: String, required: true },
});
</script>

<style scoped>

.elama-btn {
    margin-top: 20px;
}

.g-btn {
    background-color: #4285f4;
    transition: box-shadow ease-in .25s;
    margin-bottom: 20px;
}

.g-btn__icon {
    padding: 10px;
}

.elama-btn__icon img {
    width: 18px;
    height: 18px;
}

.g-btn:hover {
    box-shadow: 0 0 3px 3px rgb(66 133 244 / 30%);
}
</style>
