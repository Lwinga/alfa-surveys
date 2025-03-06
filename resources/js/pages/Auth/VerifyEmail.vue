<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { CheckCircleIcon } from '@heroicons/vue/24/outline';

defineProps({
    status: String,
});

const form = useForm({});

function submit() {
    form.post(route('verification.send'));
}
</script>

<template>
    <AuthLayout>
        <Head title="Verify Email"></Head>

        <template #title>
            <h1 class="mb-4">EMAIL VERIFICATION</h1>
        </template>

        <form @submit.prevent="submit" class="flex flex-col items-center justify-center  gap-6 w-full">
            <div v-if="status === 'verification-link-sent'" role="alert" class="alert alert-success w-full">
                <CheckCircleIcon class="w-6 h-6 stroke-current" />
                <span>A new verification link has been sent successfully.</span>
            </div>

            <div class="text-center">Please verify your email address by clicking on the link we just emailed to you.</div>

            <input type="submit" class="btn btn-primary w-full" value="Resend verification email" :disabled="form.processing">

            <Link :href="route('logout')" method="post" as="button" class="link link-primary link-hover">Logout</Link>
        </form>
    </AuthLayout>
</template>
