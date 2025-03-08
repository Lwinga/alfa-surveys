<script setup>
import { Link } from '@inertiajs/vue3';
import Logo from '@/components/Logo.vue';
import { ArrowLeftStartOnRectangleIcon, Bars3Icon, HomeIcon, UserIcon } from '@heroicons/vue/16/solid';
import AppLink from '@/components/AppLink.vue';
import { provide, readonly, ref } from 'vue';
import { toastsKey } from '@/keys.js';
import Toast from '@/components/Toast.vue';
import { generateAvatarPlaceholder } from '@/lib/utils';

const props = defineProps({
    user: Object,
});

const title = import.meta.env.VITE_APP_NAME;

/** @type {import.vue.Ref<{ id: int, type: 'error' | 'info' | 'success', message: string }[]>} */
const toasts = ref([]);

/** @type {(type: 'error' | 'info' | 'success', message: string) => void} */
function addToast(type, message) {
    let latestId = 0;
    toasts.value.forEach(({ id }) => {
        latestId = id > latestId ? id : latestId;
    });
    toasts.value.push({
        id: latestId + 1,
        type,
        message,
    });
}

/** @type {(id: number) => void} */
function removeToast(id) {
    toasts.value = toasts.value.filter((toast) => toast.id !== id);
}

provide(toastsKey, {
    toasts: readonly(toasts),
    addToast,
    removeToast,
});
</script>

<template>
    <div class="drawer lg:drawer-open">
        <input type="checkbox" id="drawer-toggle" class="drawer-toggle">
        <div class="drawer-side z-20">
            <label for="drawer-toggle" class="drawer-overlay"></label>
            <div class="bg-base-100 w-72 h-full overflow-auto" data-theme="dark">
                <div class="flex items-center gap-4 p-2 z-10 sticky top-0">
                    <Link :href="route('home')" class="btn btn-square btn-ghost">
                        <Logo class="flex-none w-12 h-12 stroke-primary fill-base-100" />
                    </Link>
                    <h2 class="flex-1 text-base-content">{{ title.toUpperCase() }}</h2>
                </div>
                <ul class="menu w-full">
                    <li>
                        <AppLink :href="route('dashboard')" text="Dashboard" :active="route().current('dashboard')">
                            <template #leading-icon="slot">
                                <HomeIcon  v-bind="slot" />
                            </template>
                        </AppLink>
                    </li>
                    <li>
                        <AppLink :href="route('profile')" text="Profile" :active="route().current('profile')">
                            <template #leading-icon="slot">
                                <UserIcon  v-bind="slot" />
                            </template>
                        </AppLink>
                    </li>
                </ul>
            </div>
        </div>

        <div class="drawer-content flex flex-col min-h-svh bg-base-200">
            <Teleport to="body">
                <div id="toast-container"  class="toast toast-top z-20">
                    <TransitionGroup
                        enter-active-class="animate__animated animate__slideInRight"
                        leave-active-class="animate__animated animate__slideOutRight absolute"
                        move-class="transition-all duration-200"
                    >
                        <!-- animate-none removes a default alert animation -->
                        <Toast
                            v-for="toast in toasts"
                            :toast="toast"
                            :key="toast.id"
                            @close="removeToast"
                            class="animate-none [--animate-duration:200ms] [--animate-delay:0]"
                        />
                    </TransitionGroup>
                </div>
            </Teleport>

            <header class="navbar flex-none bg-base-100 gap-2 shadow z-10 sticky top-0">
                <label for="drawer-toggle" class="flex-none btn btn-ghost btn-square lg:hidden">
                    <Bars3Icon class="w-6 h-6" />
                </label>
                <div class="flex-1">
                    <h2 class="flex-1 text-base-content max-sm:hidden lg:hidden">{{ title.toUpperCase() }}</h2>
                </div>
                <div class="flex-none">{{ user?.name }}</div>
                <div class="flex-none dropdown dropdown-end">
                    <div tabindex="0" role="button" class="avatar btn btn-ghost btn-circle" :class="{'placeholder': !user.photo_path}">
                        <div class="w-12 rounded-full bg-neutral/60 text-neutral-content">
                            <img v-if="user.photo_path" :src="user.photo_path" alt="Profile photo">
                            <h3 v-else>{{ generateAvatarPlaceholder(user.name) }}</h3>
                        </div>
                    </div>
                    <ul class="dropdown-content menu bg-base-100 shadow-lg rounded-xl border w-64">
                        <li>
                            <AppLink :href="route('profile')" text="Profile">
                                <template #leading-icon="slot">
                                    <UserIcon  v-bind="slot" />
                                </template>
                            </AppLink>
                        </li>
                        <li>
                            <AppLink :href="route('logout')" method="post" as="button" text="Logout">
                                <template #leading-icon="slot">
                                    <ArrowLeftStartOnRectangleIcon  v-bind="slot" />
                                </template>
                            </AppLink>
                        </li>
                    </ul>
                </div>
            </header>

            <main class="flex-1">
                <slot></slot>
            </main>

            <footer class="footer flex-none sticky bottom-0 bg-base-100 p-2 justify-center z-10 shadow">
                <div class="text-center">Copyright &copy; {{ new Date().getFullYear() }} Alfa Surveys - All rights reserved.</div>
            </footer>
        </div>
    </div>
</template>
