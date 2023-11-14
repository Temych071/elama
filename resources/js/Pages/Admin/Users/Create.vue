<template>
    <form class="card px-8 py-4" @submit.prevent="onSubmit">
        <div class="form-field mb-4">
            <label class="form-label">{{ $t('admin.users.form.name') }}</label>
            <text-input v-model="form.name"
                        :error="form.errors.name"
                        type="text"
                        required
            />
        </div>

        <div class="form-field mb-4">
            <label class="form-label">{{ $t('admin.users.form.password') }}</label>
            <text-input
                v-model="form.password"
                :error="form.errors.password"
                type="password"
                required
            />
        </div>

        <div class="form-field mb-4">
            <label class="form-label">E-Mail</label>
            <text-input v-model="form.email"
                        :error="form.errors.email"
                        type="email"
                        required
            />
        </div>

        <div class="form-field mb-4">
            <label class="form-label">{{ $t('admin.users.form.phone') }}</label>
            <text-input v-model="form.phone"
                        :error="form.errors.phone"
                        type="text"
                        mask="tel"
                        required
            />
        </div>

        <div class="form-field mb-4">
            <label class="form-label">{{ $t('admin.users.form.role') }}</label>
            <select v-model="form.role" class="select-control w-full" required>
                <option v-for="role in roles" :value="role">{{ $t('users.roles.' + role) }}</option>
            </select>
            <input-error :message="form.errors.tariff"/>
        </div>

        <div class="form-field mb-4">
            <label class="form-label">{{ $t('admin.users.form.tariff') }}</label>
            <select v-model="form.tariff" class="select-control w-full" required>
                <option v-for="tariff in tariffs" :value="tariff">{{ $t('users.tariffs.' + tariff) }}</option>
            </select>
            <input-error :message="form.errors.tariff"/>
        </div>

        <div class="form-field mb-4">
            <button type="submit" class="btn btn-md btn-primary">{{ $t('admin.users.form.saveButton') }}</button>
        </div>
    </form>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    roles: {
        type: Array,
        require: true,
    },
    tariffs: {
        type: Array,
        require: true,
    },
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    role: props.roles[0] ?? 'user',
    tariff: props.tariffs[0] ?? 'free',
});

function onSubmit() {
    form.post(route('admin.users.create.store'));
}
</script>

<script>
import layout from "@/Layouts/Admin.vue";

export default {layout};
</script>

<style scoped>

</style>
