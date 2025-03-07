<script setup>
import { CheckCircleIcon, ExclamationTriangleIcon, InformationCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { computed, onMounted, ref, Teleport, watchEffect } from 'vue';

const props = defineProps({
    toast: {
        type: Object,
        required: true,
    },
    teleport: Boolean,
});

const emit = defineEmits(['close']);

const hovered = ref(false);
const timeout = ref(false);

const className = computed(() => ({
    'alert-success': props.toast.type === 'success',
    'alert-info': props.toast.type === 'info',
    'alert-error': props.toast.type === 'error',
}));

watchEffect(() => {
    // Close a toast if a timeout didn't due to a toast being hovered
    if (!hovered.value && timeout.value) {
        emit('close', props.toast.id);
    }
});

onMounted(() => {
    setTimeout(() => {
        // Close after a timeout finished unless a toast is hovered
        if (hovered.value) {
            timeout.value = true;
        } else {
            emit('close', props.toast.id);
        }
    }, 3000);
});
</script>

<template>
    <Teleport to="#toast-container" :disabled="teleport">
        <div class="alert cursor-default" :class="className" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <CheckCircleIcon v-if="toast.type === 'success'" class="w-6 h-6 stroke-current" />
            <InformationCircleIcon v-if="toast.type === 'info'" class="w-6 h-6 stroke-current" />
            <ExclamationTriangleIcon v-if="toast.type === 'error'"  class="w-6 h-6 stroke-current" />
            <span class="flex-grow">
                <slot>{{ toast.message }}</slot>
            </span>
            <button @click="$emit('close', toast.id)" type="button" class="btn btn-circle btn-sm btn-ghost">
                <XMarkIcon  class="w-4 h-4 stroke-current" />
            </button>
        </div>
    </Teleport>
</template>
