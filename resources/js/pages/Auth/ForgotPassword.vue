<script setup>
import { EnvelopeIcon } from '@heroicons/vue/16/solid';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
import TextInput from '@/components/TextInput.vue';
import { CheckCircleIcon } from '@heroicons/vue/24/outline';

defineProps({
    status: String,
});

const form = useForm({
    email: null,
});

function submit() {
    form.post(route('password.email'));
}
</script>

<template>
    <AuthLayout>
        <Head title="Forgot Password"></Head>
        <template #title>
            <h1 class="mb-4">FORGOT PASSWORD</h1>
        </template>

        <form @submit.prevent="submit" class="flex flex-col items-center justify-center gap-6 w-full">
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

            <input type="submit" value="Email password reset link" class="btn btn-primary w-full" :disabled="form.processing">

            <div>Do you remember it? <Link :href="route('login')" class="link link-primary link-hover">Login</Link></div>
        </form>
    </AuthLayout>
</template>
