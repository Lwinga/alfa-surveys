<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { inject } from 'vue';
import { toastsKey } from '@/keys';

const props = defineProps({
    verified: Boolean,
});

defineOptions({
    layout: AppLayout
});

const toasts = inject(toastsKey);

if (props.verified) {
    toasts.addToast('success', 'Email successfully verified');
}
</script>

<template>
    <div>
        <Head title="Dashboard"></Head>
    </div>
</template>
