<script setup>
import { EnvelopeIcon, KeyIcon } from '@heroicons/vue/16/solid';
import { Head, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
import TextInput from '@/components/TextInput.vue';
import PasswordInput from '@/components/PasswordInput.vue';

const props = defineProps({
    token: String,
    email: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: null,
    password_confirmation: null,
});

function submit() {
    form.post(route('password.update'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
}
</script>

<template>
    <AuthLayout>
        <Head title="Reset Password"></Head>
        <template #title>
            <h1>RESET PASSWORD</h1>
        </template>

        <form @submit.prevent="submit" class="flex flex-col items-center justify-center gap-4 w-full">

            <TextInput
                v-model="form.email"
                id="email"
                label-text="Email"
                :error-message="form.errors.email"
                class="w-full"
            >
                <template #leading-icon="{ className }">
                    <EnvelopeIcon :class="className" />
                </template>
            </TextInput>

            <PasswordInput
                v-model="form.password"
                id="password"
                label-text="Password"
                :error-message="form.errors.password"
                class="w-full"
            >
                <template #leading-icon="{ className }">
                    <KeyIcon :class="className" />
                </template>
            </PasswordInput>

            <PasswordInput
                v-model="form.password_confirmation"
                id="password-confirmation"
                label-text="Confirm Password"
                :error-message="form.errors.password_confirmation"
                class="w-full"
            >
                <template #leading-icon="{ className }">
                    <KeyIcon :class="className" />
                </template>
            </PasswordInput>

            <input type="submit" value="Reset Password" class="btn btn-primary w-full" :disabled="form.processing">
        </form>
    </AuthLayout>
</template>
