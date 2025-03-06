<script setup>
import { Link } from '@inertiajs/vue3';
import Logo from '@/components/Logo.vue';
import { Bars3Icon } from '@heroicons/vue/16/solid';

const title = import.meta.env.VITE_APP_NAME;
</script>

<template>
    <div class="flex flex-col items-center justify-between gap-8 bg-base-200 min-h-svh">
        <header class="navbar bg-base-100 gap-4 sticky top-0 z-10 shadow">
            <Link :href="route('home')" class="btn btn-square btn-ghost">
                <Logo class="flex-none w-12 h-12 stroke-primary fill-base-100" />
            </Link>
            <h2 class="flex-1 text-base-content">{{ title.toUpperCase() }}</h2>
            <nav class="flex-none">
                <div class="dropdown dropdown-end md:dropdown-open">
                    <button class="btn btn-ghost md:hidden">
                        <Bars3Icon class="w-6 h-6 fill-base-content" />
                    </button>
                    <ul class="menu max-md:dropdown-content max-md:bg-base-100 max-md:shadow-lg max-md:rounded-xl max-md:border max-md:w-64 md:menu-horizontal md:gap-2">
                        <li><Link :href="route('home')">Home</Link></li>
                        <li><Link :href="route('login')">Login</Link></li>
                        <li><Link :href="route('register')" class="btn btn-outline btn-primary btn-sm">Register</Link></li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="card rounded-box shadow-lg border bg-base-100 max-sm:card-compact w-full max-w-screen-sm">
            <div class="card-body justify-center items-center">
                <div class="text-center text-base-content">
                    <slot name="title"></slot>
                </div>
                <slot></slot>
            </div>
        </div>

        <footer class="footer bg-base-100 p-2 justify-center">
            <div class="text-center">Copyright &copy; {{ new Date().getFullYear() }} Alfa Surveys - All rights reserved.</div>
        </footer>
    </div>
</template>
