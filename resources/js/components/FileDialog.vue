<script setup>
import { CloudArrowUpIcon } from '@heroicons/vue/16/solid';
import Modal from './Modal.vue';
import { useDropZone, useFileDialog } from '@vueuse/core';
import { computed, ref, useTemplateRef } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { CircleStencil, Cropper } from 'vue-advanced-cropper';

const props = defineProps({
    multiple: Boolean,
    accept: {
        type: String,
        default: 'image/*',
    },
    crop: Boolean,
    disabled: Boolean,
});

const visible = defineModel({
    type: Boolean,
});

const emit = defineEmits(['submit']);

const files = ref(new Set([]));
const dropZone = useTemplateRef('drop-zone');
const cropper = useTemplateRef('cropper');

const { open, onChange } = useFileDialog({
    multiple: props.multiple,
    accept: props.accept,
});

const { isOverDropZone } = useDropZone(dropZone, {
    multiple: props.multiple,
    dataTypes: (types) => {
        let accept = props.accept.replace('*', '');
        for (let type of types) {
            if (type.startsWith(accept)) {
                return true;
            }
        }
        return false;
    },
    preventDefaultForUnhandled: true,
    onDrop: receiveFiles,
});

onChange(receiveFiles);

const dropClass = computed(() => isOverDropZone.value ? 'border-primary' : 'border-primary-content');
const firstFile = computed(() => [...files.value][0]);

function receiveFiles(fileList) {
    if (!fileList || fileList.length === 0) return;
    if (props.multiple) {
        for (let file of fileList) {
            files.value.add(file);
        }
    } else {
        files.value.clear();
        files.value.add(fileList[0]);
    }
}

function getFileURL(file) {
    return URL.createObjectURL(file);
}

function submit() {
    if (props.crop) {
        const { canvas } = cropper.value.getResult();
        canvas.toBlob(blob => {
            emit('submit', blob);
        }, firstFile.value.type);
    } else {
        emit('submit', files);
    }
}
</script>

<template>
    <Modal v-model="visible" @close="files = new Set([])">
        <div v-if="firstFile && crop" class="relative h-72 mb-2 rounded-xl overflow-hidden">
            <Cropper
                class="h-full"
                ref="cropper"
                :src="getFileURL(firstFile)"
                :stencil-props="{
                    handlers: {},
                    movable: false,
                    resizable: false,
                    aspectRatio: 1,
                }"
                :resize-image="{
                    adjustStencil: false
                }"
                :canvas="{
                    height: 256,
                    width: 256,
                }"
                image-restriction="stencil"
                :stencil-component="CircleStencil"
            />
            <button
                @click="files.delete(firstFile)"
                type="button"
                class="btn btn-circle btn-error btn-outline btn-xs absolute top-2 right-2"
            >
                <XMarkIcon class="w-4 h-4 fill-current" />
            </button>
        </div>

        <div
            v-else
            @click="open"
            ref="drop-zone"
            class="flex flex-col justify-center items-center h-56 border-4 border-dotted rounded-xl cursor-default mb-2"
            :class="dropClass"
        >
            <CloudArrowUpIcon class="w-24 h-24 text-primary-content" />
            <p class="text-primary-content">Drag file(s) here or click to select</p>
        </div>

        <div v-if="!crop" class="flex flex-wrap overflow-auto">
            <div v-for="file in files" class="w-20 h-28 border rounded-lg overflow-hidden relative me-2 mb-2">
                <figure class="h-[84px]">
                    <img
                        v-if="file.type.startsWith('image')"
                        :src="getFileURL(file)"
                        :alt="file.name"
                        class="w-full h-full object-cover"
                    >
                    <div v-else class="flex justify-center items-center h-full overflow-hidden p-2">
                        <p class="text-primary-content text-center break-all">{{ file.name }}</p>
                    </div>
                </figure>
                <div class="p-1 text-xs overflow-ellipsis line-clamp-1" :title="file.name">{{ file.name }}</div>
                <button
                    @click="files.delete(file)"
                    type="button"
                    class="btn btn-circle btn-error btn-outline btn-xs absolute top-1 right-1"
                >
                    <XMarkIcon class="w-4 h-4 fill-current" />
                </button>
            </div>
        </div>

        <template #action>
            <button @click="submit" type="button" class="btn btn-primary" :disabled="!firstFile || disabled">
                Submit
            </button>
            <button @click="visible = false" type="button" class="btn btn-primary btn-outline" :disabled>
                Cancel
            </button>
        </template>
    </Modal>
</template>
