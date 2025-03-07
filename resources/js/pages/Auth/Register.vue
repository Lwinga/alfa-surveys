<script setup>
import { EnvelopeIcon, KeyIcon, UserIcon } from '@heroicons/vue/16/solid';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
import TextInput from '@/components/TextInput.vue';
import PasswordInput from '@/components/PasswordInput.vue';

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
});

function submit() {
    form.post(route('register.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
}
</script>

<template>
    <AuthLayout>
        <Head title="Register"></Head>
        <template #title>
            <h1>REGISTER</h1>
        </template>

        <form @submit.prevent="submit" class="flex flex-col items-center justify-center gap-4 w-full">
            <TextInput
                v-model="form.name"
                id="name"
                label-text="Name"
                placeholder="Full Name"
                :error-message="form.errors.name"
                class="w-full"
            >
                <template #leading-icon="slot">
                    <UserIcon  v-bind="slot" />
                </template>
            </TextInput>

            <TextInput
                v-model="form.email"
                id="email"
                label-text="Email"
                :error-message="form.errors.email"
                class="w-full"
            >
                <template #leading-icon="slot">
                    <EnvelopeIcon  v-bind="slot" />
                </template>
            </TextInput>

            <PasswordInput
                v-model="form.password"
                id="password"
                label-text="Password"
                :error-message="form.errors.password"
                class="w-full"
            >
                <template #leading-icon="slot">
                    <KeyIcon  v-bind="slot" />
                </template>
            </PasswordInput>

            <PasswordInput
                v-model="form.password_confirmation"
                id="password-confirmation"
                label-text="Confirm Password"
                :error-message="form.errors.password_confirmation"
                class="w-full"
            >
                <template #leading-icon="slot">
                    <KeyIcon  v-bind="slot" />
                </template>
            </PasswordInput>

            <input type="submit" value="Register" class="btn btn-primary w-full" :disabled="form.processing">

            <div>Already have an account? <Link :href="route('login')" class="link link-primary link-hover">Login</Link></div>
        </form>
    </AuthLayout>
</template>
