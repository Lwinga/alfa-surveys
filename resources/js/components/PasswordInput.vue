<script setup>
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/16/solid';
import { computed, ref } from 'vue';
import TextInput from './TextInput.vue';

defineProps({
    id: {
        type: String,
        required: true,
    },
    labelText: {
        type: String,
        required: true,
    },
    placeholder: String,
    errorMessage: String,
});

const model = defineModel();

const passwordVisible = ref(false);
const inputType = computed(() => passwordVisible.value ? 'text' : 'password');
</script>

<template>
    <TextInput :id :labelText :placeholder :errorMessage>
        <template #label-end>
            <slot name="label-end"></slot>
        </template>
        
        <template #leading-icon="{ className }">
            <slot name="leading-icon" :className></slot>
        </template>

        <input v-model="model" :type="inputType" :placeholder="placeholder ?? labelText" :id class="grow">
        <div class="swap" :class="{ 'swap-active': passwordVisible }" @click="passwordVisible = !passwordVisible">
            <EyeSlashIcon class="swap-on w-5 h-5 fill-slate-500" />
            <EyeIcon class="swap-off w-5 h-5 fill-slate-500" />
        </div>
    </TextInput>
</template>
