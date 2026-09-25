<script setup lang="ts">
import { ref, watch, nextTick } from 'vue';

interface Props {
    modelValue: boolean;
    title?: string;
    size?: 'sm' | 'md' | 'lg' | 'xl';
    modal?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
    modal: true,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const dialogRef = ref<HTMLDialogElement | null>(null);

async function updateDialog(open: boolean) {
    await nextTick();

    const dialog = dialogRef.value;

    if (!dialog) {
        return;
    }

    if (open) {
        if (!dialog.open) {
            if (props.modal) {
                dialog.showModal();
            } else {
                dialog.show();
            }
        }
    } else if (dialog.open) {
        dialog.close();
    }
}

watch(
    () => props.modelValue,
    (open) => {
        updateDialog(open);
    },
    { immediate: true },
);

function onClose() {
    if (props.modelValue) {
        emit('update:modelValue', false);
    }
}

function onBackdropClick(event: MouseEvent) {
    if (event.target === dialogRef.value) {
        onClose();
    }
}
</script>

<template>
    <dialog
        ref="dialogRef"
        class="m-auto w-full rounded-lg bg-white p-0 shadow-xl backdrop:bg-black/40 dark:bg-neutral-900"
        :class="{
            'max-w-sm': size === 'sm',
            'max-w-md': size === 'md',
            'max-w-lg': size === 'lg',
            'max-w-xl': size === 'xl',
        }"
        @close="onClose"
        @click="onBackdropClick"
    >
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2
                    v-if="title"
                    class="text-lg font-semibold text-neutral-900 dark:text-neutral-100"
                >
                    {{ title }}
                </h2>

                <button
                    type="button"
                    class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200"
                    @click="onClose"
                >
                    ✕
                </button>
            </div>

            <slot />

            <div
                v-if="$slots.footer"
                class="mt-6 flex justify-end gap-2"
            >
                <slot name="footer" />
            </div>
        </div>
    </dialog>
</template>