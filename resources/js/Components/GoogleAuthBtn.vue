<template>
    <a :href="route('auth.google.redirect')">
        <div class="ddddd flex w-full items-center text-white font-bold text-sm rounded g-btn">
            <div class="g-btn__icon bg-white rounded m-0.5">
                <img src="/icons/btn_google_light_normal.svg" alt="">
            </div>
            <div class="mx-auto text-center">{{ props.title }}</div>
        </div>
    </a>
</template>

<script setup>
const props = defineProps({
    title: { type: String, required: true },
});
</script>

<style scoped>
.g-btn {
    background-color: #4285f4;
    transition: box-shadow ease-in .25s;
}

.g-btn__icon {
    padding: 10px;
}

.g-btn:hover {
    box-shadow: 0 0 3px 3px rgb(66 133 244 / 30%);
}
</style>
