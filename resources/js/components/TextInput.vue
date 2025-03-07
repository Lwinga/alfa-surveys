<script setup>
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
</script>

<template>
    <div class="form-control">
        <div class="label">
            <label :for="id" class="label-text">{{ labelText }}</label>
            <slot name="label-end"></slot>
        </div>
        <label class="input input-bordered flex items-center gap-4">
            <slot name="leading-icon" class="w-5 h-5 fill-slate-500"></slot>
            <slot>
                <input v-model="model" type="text" :placeholder="placeholder ?? labelText" :id class="grow">
            </slot>
        </label>
        <div class="label" v-if="errorMessage">
            <span class="label-text text-error">{{ errorMessage }}</span>
        </div>
    </div>
</template>
