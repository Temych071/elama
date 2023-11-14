<template>
    <div class="relative">
        <!--        <input v-model="form.email" class="" name="email" type="email" placeholder="Email*" required autofocus autocomplete="username">-->

        <input name="password"
               class="form-control"
               :type="passwordFieldType"
               :value="modelValue"
               @input="$emit('update:modelValue', $event.target.value)"
               required
               :placeholder="placeholder"
        >
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 cursor-pointer">
            <img src="/icons/eye-slash.svg" class="pst-h" v-if="isVisible" @click="switchVisibility">
            <img src="/icons/eye.svg" class="pst-s" v-else @click="switchVisibility">
        </div>
    </div>
</template>

<script>
export default {

    props: ['modelValue', 'placeholder'],

    emits: ['update:modelValue'],

    data() {
        return {
            passwordFieldType: 'password',
        };
    },

    computed: {
        isVisible() {
            return this.passwordFieldType !== 'password';
        },
    },

    methods: {
        switchVisibility() {
            this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password';
        },
    }
}
</script>
