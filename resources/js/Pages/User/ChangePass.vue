<template>
    <card class="card-md m-auto">
        <form @submit.prevent="onSubmit">
            <validation-errors/>

            <div class="form-field mt-4">
                <label class="form-label">Текущий пароль</label>
                <text-input v-model="form.current_pass"
                            required
                            type="password"
                            placeholder="Введите текущий пароль"
                            autocomplete="current-password"
                />
            </div>
            <div class="form-field mt-4">
                <label class="form-label">Новый пароль</label>
                <text-input v-model="form.new_pass"
                            required
                            type="password"
                            placeholder="Введите новый пароль"
                            autocomplete="new-password"
                />
            </div>
            <div class="form-field mt-4">
                <label class="form-label">Подтверждение нового пароля</label>
                <text-input v-model="form.new_pass_confirmation"
                            required
                            type="password"
                            placeholder="Введите новый пароль ещё раз"
                            autocomplete="new-password"
                />
            </div>
            <div class="form-field mt-4">
                <button type="submit" class="btn btn-md btn-primary w-full">Отправить</button>
            </div>
        </form>
    </card>
</template>

<script setup>
import Card from "@/Components/MetrikaSummary/Feed/Card.vue";
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const form = useForm({
    current_pass: '',
    new_pass: '',
    new_pass_confirmation: '',
});

function onSubmit() {
    form.post(route('user.change-pass.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<script>
import Layout from '@/Layouts/Authenticated.vue';
export default {layout: Layout}
</script>
