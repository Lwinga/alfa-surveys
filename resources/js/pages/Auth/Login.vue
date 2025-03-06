<script setup>
import { EnvelopeIcon, KeyIcon } from '@heroicons/vue/16/solid';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
import TextInput from '@/components/TextInput.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { CheckCircleIcon } from '@heroicons/vue/24/outline';

defineProps({
    status: String,
});

const form = useForm({
    email: null,
    password: null,
    remember: false,
});

function submit() {
    form.post(route('login.store'), {
        onFinish: () => {
            form.reset('password');
        },
    });
}
</script>

<template>
    <AuthLayout>
        <Head title="Login"></Head>
        <template #title>
            <h1>LOGIN</h1>
        </template>

        <form @submit.prevent="submit" class="flex flex-col items-center justify-center gap-4 w-full">
            <div v-if="status" role="alert" class="alert alert-success w-full">
                <CheckCircleIcon class="w-6 h-6 stroke-current" />
                <span>{{ status }}</span>
            </div>

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
                <template #label-end>
                    <Link :href="route('password.request')" class="label-text link link-primary link-hover">Forgot password?</Link>
                </template>
                <template #leading-icon="{ className }">
                    <KeyIcon :class="className" />
                </template>
            </PasswordInput>

            <div class="form-control w-full">
                <div class="label justify-start gap-4">
                    <input v-model="form.remember" type="checkbox" id="remember" class="checkbox checkbox-primary">
                    <label for="remember" class="label-text">Remember me</label>
                </div>
            </div>

            <input type="submit" value="Login" class="btn btn-primary w-full" :disabled="form.processing">

            <div>Do you have an account? <Link :href="route('register')" class="link link-primary link-hover">Register</Link></div>
        </form>
    </AuthLayout>
</template>
