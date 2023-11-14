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
            <label class="form-label">E-Mail</label>
            <text-input v-model="form.email"
                        :error="form.errors.email"
                        type="text"
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

        <div class="flex justify-between">
            <div class="form-field">
                <button type="submit" class="btn btn-md btn-primary">{{ $t('admin.users.form.saveButton') }}</button>
            </div>
            <div
                class="btn btn-md btn-error-light cursor-pointer"
                @click="onDelete()"
            >
                Удалить
            </div>
        </div>
    </form>
</template>

<script setup>
import {useForm, router} from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    userId: {
        type: [Number, null],
        require: false,
        default: null,
    },
    userData: {
        type: [Object, null],
        require: false,
        default: null,
    },
    roles: {
        type: Array,
        require: true,
    },
    tariffs: {
        type: Array,
        require: true,
    },
});

const form = useForm(props.userData ?? {
    name: '',
    email: '',
    phone: '',
    role: props.roles[0] ?? 'user',
    tariff: props.tariffs[0] ?? 'free',
});

function onSubmit() {
    if (props.userId === null) {
        form.post(route('admin.users.create.store'));
    } else {
        form.put(route('admin.users.edit.store', props.userId));
    }
}

const onDelete = () => {
    const res = confirm('Вы действительно хотите удалит данного пользователя?');
    if (res) {
        return router.delete(route('admin.users.edit.delete', props.userId))
    }
}

</script>

<script>
import layout from "@/Layouts/Admin.vue";

export default {layout};
</script>
