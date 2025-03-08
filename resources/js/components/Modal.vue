<script setup>
import { onMounted, useTemplateRef, watchEffect } from 'vue';

defineProps({
    title: String,
    message: String,
    closeOnClickOutside: Boolean,
});

const visible = defineModel({
    type: Boolean,
});

const emit = defineEmits(['accept', 'cancel', 'close']);

const modal = useTemplateRef('modal');

onMounted(() => {
    watchEffect(() => {
        if (visible.value) {
            modal.value.showModal();
        } else {
            modal.value.close();
        }
    });
});

function acceptModal() {
    emit('accept');
    visible.value = false;
}

function cancelModal() {
    emit('cancel');
    visible.value = false;
}
</script>

<template>
    <Teleport to="body">
        <dialog class="modal" ref="modal" @close="$emit('close')">
            <div class="modal-box">
                <slot>
                    <h3 class="mb-4">{{ title }}</h3>
                    <p>{{ message }}</p>
                </slot>
                <div class="modal-action">
                    <slot name="action">
                        <button @click="acceptModal" type="button" class="btn btn-primary">Accept</button>
                        <button @click="cancelModal" type="button" class="btn btn-primary btn-outline">Cancel</button>
                    </slot>
                </div>
            </div>
            <div v-if="closeOnClickOutside" @click="visible = false" method="dialog" class="modal-backdrop"></div>
        </dialog>
    </Teleport>
</template>
