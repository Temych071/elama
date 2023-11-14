<template>
    <div class="card px-8 pt-1">
        <div class="flex justify-between">
            <h3 class="card-title-sm flex flex-row flew-nowrap">
                <slot name="title"/>
            </h3>

            <div class="card-time">
                <slot name="time"/>
            </div>
        </div>

        <div class="card-body mt-2 mb-4">
            <slot/>
        </div>

        <div class="card-footer">
            <slot name="footer"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "Card"
}
</script>
