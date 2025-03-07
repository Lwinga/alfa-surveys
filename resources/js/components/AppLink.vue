<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    text: String,
    active: Boolean,
});
</script>

<template>
    <Link class="flex items-center gap-2" :class="{ 'active': active }">
        <div class="flex-none">
            <slot name="leading-icon" class="w-4 h-4 fill-current"></slot>
        </div>
        <div class="flex-1">
            <slot>{{ text }}</slot>
        </div>
        <div class="flex-none">
            <slot name="trailing-icon"></slot>
        </div>
    </Link>
</template>
