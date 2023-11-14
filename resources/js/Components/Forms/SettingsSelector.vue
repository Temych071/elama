<template>
    <div>
        <h2 v-if="title" class="settings-title mb-6">{{ title }}</h2>

        <div class="settings-group grid grid-cols-2 gap-x-2" v-if="show">
            <div class="settings-group__list settings-group__list_left">
                <Draggable
                    class="settings-group__zone"
                    :class="{'settings-group__zone_empty': isEmpty(available)}"
                    :list="available"
                    :sort="false"
                    group="people"
                    itemKey="name"
                    @change="changeAvailable"
                >
                    <template #item="{ element, index }">
                        <div class="settings-group__item">
                            <div class="flex-grow">{{ element.name }}</div>
                            <button @click.prevent.self="select(index)" class="hover:bg-gray-50 p-2 flex-shrink-0">
                                <img src="/icons/right-arrow.svg" alt="">
                            </button>
                        </div>
                    </template>
                </Draggable>
            </div>

            <div class="settings-group__list settings-group__list_right">
                <div class="settings-group__head p-2 mb-2">
                    <div class="mr-9 md:block hidden">№</div>
                    <h3>Показатель</h3>
                </div>
                <Draggable
                    class="settings-group__zone"
                    :list="selected"
                    group="people"
                    itemKey="name"
                    @change="changeSelected"
                >
                    <template #item="{ element, index }">
                        <div class="settings-group__item">
                            <div class="flex flex-row justify-start mr-2 flex-shrink-0">
                                <span class="md:inline hidden mr-2">{{ index + 1 }}</span>
                                <img src="/icons/menu-sm.svg" alt="">
                            </div>

                            <div class="flex flex-row justify-between w-full flex-grow">
                                <div>{{ element.name }}</div>
                                <button
                                    class="font-medium leading-none hover:bg-gray-50 px-1 md:inline hidden"
                                    @click.prevent.self="remove(index)"
                                >
                                    -
                                </button>
                            </div>
                        </div>
                    </template>
                </Draggable>
            </div>
        </div>

        <div v-else class="text-center flex flex-col items-center mb-6">
            <div> {{reasonNotShowing}}  </div>
            <Link :href="routeTo">
                <button class="btn btn-md btn-primary mx-3 mt-3">{{ $t('campaigns.feed.btn.connect') }}</button>
            </Link>
        </div>
    </div>
</template>

<script setup>
import Draggable from 'vuedraggable';
import {ref} from "vue";
import {isEmpty} from "@/utils";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
    },
    availableSettings: {
        required: true,
        type: Array,
    },
    selectedSettings: {
        required: false,
        type: Array,
        default: [],
    },
    show: {
        type: Boolean,
        default: true,
    },
    reasonNotShowing: {
        type: String,
        default: '',
    },
    routeTo: {
        type: String,
        default: null,
    }
});

const emits = defineEmits(['selected'])

const available = ref([...props.availableSettings]);

const selected = ref([...props.selectedSettings]);

const select = (index) => {
    const newAvailable = [...available.value];
    const item = newAvailable.splice(index, 1)[0];
    available.value = newAvailable;

    const newSelected = [...selected.value];
    newSelected.push(item);
    selected.value = newSelected;
    emits('selected', selected.value);
}

const remove = (index) => {
    // console.log(index);
    const newSelected = [...selected.value];
    const item = newSelected.splice(index, 1)[0];
    selected.value = newSelected;

    const newAvailable = [...available.value];
    newAvailable.push(item);
    available.value = newAvailable;
    emits('selected', selected.value);
}

const changeSelected = () => emits('selected', selected.value);

</script>

<style scoped lang="scss">

.settings-title {
    font-weight: 900;
    font-size: 16px;
    line-height: 20px;
    letter-spacing: 0.2px;
    color: #252733;
}

.settings-group {
    &__zone,
    &__list {
        height: 100%;

        &_left {
            flex-basis: 200px;
        }

        &_right {
            flex-basis: 400px;
        }
    }

    &__zone_empty {
        border: solid 1px #e1e7ee;
    }

    &__head {
        display: flex;
        align-items: center;
        padding: 8px 16px;
        color: #223354;
        font-weight: 700;
        background: #F6F8FA;
    }

    .sortable-ghost {
        opacity: 0.5;
        background: #c8ebfb;
    }

    &__item {
        display: flex;
        justify-content: space-between;
        flex-direction: row;
        align-items: center;

        min-height: auto;
        padding: 2px 9px 2px 14px;
        margin-bottom: 6px;

        background: #FFFFFF;
        border: 1px solid #CCCEDD;
        border-radius: 6px;

        font-size: 13px;
        letter-spacing: 0.2px;
        color: #252733;

        user-select: none;
        cursor: pointer;
    }
}
</style>
