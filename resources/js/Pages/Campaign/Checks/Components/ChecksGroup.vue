<template>
    <div class="checks-group mt-8">
        <div class="flex flex-row justify-start items-center">
            <h3 class="font-black p-2">{{ $t('checks.group.names.' + groupName) }}</h3>
            <span v-if="score.failed > 0">
                <span class="text-error">
                    {{ $t('checks.group.failsCounter', {failed: score.failed, total: score.total}) }}
                </span>
                {{ $t('checks.group.fails') }}
            </span>
            <span v-else>{{ $t('checks.group.noFails') }}</span>
        </div>

        <div>
            <check-item
                v-for="check in checks"
                :check="check"
                :key="check.rule"
                :group-name="groupName"
            />
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted} from "vue";
import CheckItem from "@/Pages/Campaign/Checks/Components/CheckItem.vue";

const props = defineProps({
    groupName: {
        type: String,
        required: true,
    },
    groupChecks: {
        type: Array,
        required: true,
    },
});

const checks = computed(() => {
    return props.groupChecks.sort((a, b) => b.failedObjects.length - a.failedObjects.length);
});

const calcChecksGroupScore = (checksGroup) => {
    let res = {
        total: checksGroup.length,
        failed: 0,
    };

    for (let i in checksGroup) {
        if (checksGroup[i].failedObjects.length) {
            res.failed++;
        }
    }

    return res;
}

const score = computed(() => calcChecksGroupScore(props.groupChecks));
</script>
