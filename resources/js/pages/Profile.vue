<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import TextInput from '@/components/TextInput.vue';
import { EnvelopeIcon, KeyIcon, UserIcon } from '@heroicons/vue/16/solid';
import PasswordInput from '@/components/PasswordInput.vue';
import { inject, ref } from 'vue';
import { toastsKey } from '@/keys';

const props = defineProps({
    user: Object,
});

defineOptions({
    layout: AppLayout
});

const userInfoForm = useForm({
    name: props.user?.name,
    email: props.user?.email,
});

const changePasswordForm = useForm({
    current_password: null,
    password: null,
    password_confirmation: null,
});

const toasts = inject(toastsKey);

function submitUserInfo() {
    userInfoForm.put(route('user-profile-information.update'), {
        onSuccess: () => {
            toasts.addToast('success', 'User information successfully updated');
        }
    });
}

function submitChangePassword() {
    changePasswordForm.put(route('user-password.update'), {
        preserveState: false,
        onSuccess: () => {
            toasts.addToast('success', 'Password successfully updated');
        },
    });
}
</script>

<template>
    <div class="flex flex-col gap-6 p-6">
        <Head title="Profile"></Head>
        <div class="card rounded-box shadow border bg-base-100 max-sm:card-compact">
            <div class="card-body xl:flex-row">
                <div class="card-title xl:w-64 xl:flex-none xl:items-start">User Information</div>

                <form @submit.prevent="submitUserInfo" class="flex flex-col items-center justify-center gap-4 xl:flex-1">
                    <TextInput
                        v-model="userInfoForm.name"
                        id="name"
                        label-text="Name"
                        placeholder="Full Name"
                        :error-message="userInfoForm.errors.updateProfileInformation?.name"
                        class="w-full"
                    >
                        <template #leading-icon="slot">
                            <UserIcon  v-bind="slot" />
                        </template>
                    </TextInput>

                    <TextInput
                        v-model="userInfoForm.email"
                        id="email"
                        label-text="Email"
                        :error-message="userInfoForm.errors.updateProfileInformation?.email"
                        class="w-full"
                    >
                        <template #leading-icon="slot">
                            <EnvelopeIcon  v-bind="slot" />
                        </template>
                    </TextInput>

                    <input
                        type="submit"
                        value="Update"
                        class="btn btn-primary w-full"
                        :disabled="userInfoForm.processing || !userInfoForm.isDirty"
                    >
                </form>
            </div>
        </div>

        <div class="card rounded-box shadow border bg-base-100 max-sm:card-compact">
            <div class="card-body xl:flex-row">
                <div class="card-title xl:w-64 xl:flex-none xl:items-start">Change Password</div>

                <form @submit.prevent="submitChangePassword" class="flex flex-col items-center justify-center gap-4 xl:flex-1">
                    <PasswordInput
                        v-model="changePasswordForm.current_password"
                        id="current_password"
                        label-text="Current Password"
                        :error-message="changePasswordForm.errors.updatePassword?.current_password"
                        class="w-full"
                    >
                        <template #leading-icon="slot">
                            <KeyIcon  v-bind="slot" />
                        </template>
                    </PasswordInput>

                    <PasswordInput
                        v-model="changePasswordForm.password"
                        id="password"
                        label-text="Password"
                        :error-message="changePasswordForm.errors.updatePassword?.password"
                        class="w-full"
                    >
                        <template #leading-icon="slot">
                            <KeyIcon  v-bind="slot" />
                        </template>
                    </PasswordInput>

                    <PasswordInput
                        v-model="changePasswordForm.password_confirmation"
                        id="password-confirmation"
                        label-text="Confirm Password"
                        :error-message="changePasswordForm.errors.updatePassword?.password_confirmation"
                        class="w-full"
                    >
                        <template #leading-icon="slot">
                            <KeyIcon  v-bind="slot" />
                        </template>
                    </PasswordInput>

                    <input
                        type="submit"
                        value="Update"
                        class="btn btn-primary w-full"
                        :disabled="changePasswordForm.processing || !changePasswordForm.isDirty"
                    >
                </form>
            </div>
        </div>
    </div>
</template>
