<template>
    <div v-show="message">
        <p class="text-error text-sm w-full">
            {{ message }}
        </p>
    </div>
</template>

<script>
export default {
    props: ['message']
}
</script>
