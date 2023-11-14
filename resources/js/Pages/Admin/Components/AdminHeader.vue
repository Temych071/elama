<template>
    <div class="w-full flex flex-row-reverse">
        <div class="font-bold text-sm leading-4">
            {{ account.name }} ({{ account.email }})
        </div>
    </div>
</template>

<script>
export default {
    name: "AdminHeader",
    computed: {
        account() {
            return this.$page.props.auth.user;
        },
    },
}
</script>

<style scoped>

</style>
