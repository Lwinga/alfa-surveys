<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import TextInput from '@/components/TextInput.vue';
import { EnvelopeIcon, KeyIcon, PencilSquareIcon, TrashIcon, UserIcon } from '@heroicons/vue/16/solid';
import PasswordInput from '@/components/PasswordInput.vue';
import { inject, ref } from 'vue';
import { toastsKey } from '@/keys';
import { generateAvatarPlaceholder } from '@/lib/utils';
import FileDialog from '@/components/FileDialog.vue';

const props = defineProps({
    user: Object,
    verified: Boolean,
});

defineOptions({
    layout: AppLayout
});

const photoForm = useForm({
    photo: null,
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

const fileDialogVisible = ref(false);

if (props.verified) {
    toasts.addToast('success', 'Email successfully verified');
}

function uploadPhoto(photo) {
    photoForm.photo = photo;
    photoForm.post(route('user.photo'), {
        preserveScroll: 'errors',
        onSuccess: () => {
            toasts.addToast('success', 'Photo successfully updated');
        },
        onFinish: () => {
            fileDialogVisible.value = false;
        },
    });
}

function removePhoto() {
    router.delete(route('user.photo'), {
        onSuccess: () => {
            toasts.addToast('success', 'Photo successfully removed');
        }
    });
}

function submitUserInfo() {
    userInfoForm.put(route('user-profile-information.update'), {
        preserveScroll: 'errors',
        onSuccess: () => {
            toasts.addToast('success', 'User information successfully updated');
        }
    });
}

function submitChangePassword() {
    changePasswordForm.put(route('user-password.update'), {
        preserveScroll: 'errors',
        onSuccess: () => {
            toasts.addToast('success', 'Password successfully updated');
            changePasswordForm.reset();
        },
        onFinish: () => {
            changePasswordForm.reset();
        }
    });
}
</script>

<template>
    <div class="flex flex-col gap-6 p-6">
        <Head title="Profile"></Head>
        
        <div class="card rounded-box shadow border bg-base-100 max-sm:card-compact">
            <div class="card-body xl:flex-row">
                <div class="card-title xl:w-64 xl:flex-none xl:items-start">Photo</div>

                <div class="xl:flex-1">
                    <div class="flex justify-between items-center gap-4 max-sm:flex-col">
                        <div class="avatar"  :class="{'placeholder': !user.photo_path}">
                            <div class="w-24 mask mask-squircle bg-neutral/60 text-neutral-content">
                                <img v-if="user.photo_path" :src="user.photo_path" alt="Profile photo">
                                <h1 v-else>{{ generateAvatarPlaceholder(user.name) }}</h1>
                            </div>
                        </div>
                        <h3 class="flex-grow font-semibold hidden sm:block">{{ user.name }}</h3>
                        <div class="flex gap-2">
                            <FileDialog
                                v-model="fileDialogVisible"
                                @submit="uploadPhoto"
                                accept="image/*"
                                crop
                                :close-on-click-outside="!photoForm.processing"
                                :disabled="photoForm.processing"
                            />
                            <button v-if="user.photo_path" @click="removePhoto" type="button" class="btn btn-error btn-outline">
                                <TrashIcon class="w-6 h-6 fill-current" />
                                Remove
                            </button>
                            <button @click="fileDialogVisible = true" type="button" class="btn btn-primary">
                                <PencilSquareIcon class="w-6 h-6 fill-current" />
                                Update
                            </button>
                        </div>
                    </div>
                    <p v-if="photoForm.errors.updatePhoto?.photo" class="label-text text-error">
                        {{ photoForm.errors.updatePhoto?.photo }}
                    </p>
                </div>
            </div>
        </div>

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
