<template>
    <div class="card px-5 h-full flex flex-col justify-between" :style="{paddingTop: '6px', paddingBottom: '12px'}">
        <div class="text-[13px] font-normal text-black leading-[16px] flex flex-row flew-nowrap">
            <slot name="title"/>
        </div>

        <div class="card-body mt-2">
            <slot/>
        </div>

        <div class="card-footer">
            <slot name="footer"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "CardNotification"
}
</script>

<style scoped>

</style>
