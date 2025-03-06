<script setup>
import { Link } from '@inertiajs/vue3';
import Logo from '@/components/Logo.vue';
import { ArrowLeftStartOnRectangleIcon, Bars3Icon, HomeIcon, UserIcon } from '@heroicons/vue/16/solid';
import AppLink from '@/components/AppLink.vue';

const props = defineProps({
    user: Object,
});

const title = import.meta.env.VITE_APP_NAME;

function generateAvatarPlaceholder() {
    let names = props.user?.name?.split(' ');
    let placeholder = '';

    // A placeholder should have a maximum of 2 characters
    for (let i = 0; i < names?.length && i < 2; i++) {
        placeholder += names[i].charAt(0);
    }

    return placeholder.toUpperCase();
}
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
                            <template #leading-icon="{ className }">
                                <HomeIcon :class="className" />
                            </template>
                        </AppLink>
                    </li>
                    <li>
                        <AppLink href="" text="Profile">
                            <template #leading-icon="{ className }">
                                <UserIcon :class="className" />
                            </template>
                        </AppLink>
                    </li>
                </ul>
            </div>
        </div>
        <div class="drawer-content flex flex-col min-h-svh bg-base-200">
            <header class="navbar flex-none bg-base-100 gap-2 shadow z-10 sticky top-0">
                <label for="drawer-toggle" class="flex-none btn btn-ghost btn-square lg:hidden">
                    <Bars3Icon class="w-6 h-6" />
                </label>
                <div class="flex-1">
                    <h2 class="flex-1 text-base-content max-sm:hidden lg:hidden">{{ title.toUpperCase() }}</h2>
                </div>
                <div class="flex-none">{{ user?.name }}</div>
                <div class="flex-none dropdown dropdown-end">
                    <div tabindex="0" role="button" class="avatar placeholder btn btn-ghost btn-circle">
                        <div class="w-12 rounded-full bg-base-300 text-base-content">
                            <h3>{{ generateAvatarPlaceholder() }}</h3>
                        </div>
                    </div>
                    <ul class="dropdown-content menu bg-base-100 shadow-lg rounded-xl border w-64">
                        <li>
                            <AppLink href="" text="Profile">
                                <template #leading-icon="{ className }">
                                    <UserIcon :class="className" />
                                </template>
                            </AppLink>
                        </li>
                        <li>
                            <AppLink :href="route('logout')" method="post" as="button" text="Logout">
                                <template #leading-icon="{ className }">
                                    <ArrowLeftStartOnRectangleIcon :class="className" />
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
