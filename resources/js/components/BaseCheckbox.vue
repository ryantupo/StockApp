<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    modelValue: boolean;
    label?: string;
    name: string;
    error?: string | string[];
}

const props = defineProps<Props>();
const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const id = computed(() => props.name);

function updateValue(event: Event) {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', target.checked);
}

const firstError = computed(() => {
    if (!props.error) {
        return '';
    }

    return Array.isArray(props.error) ? props.error[0] : props.error;
});
</script>

<template>
    <div class="mb-4 flex flex-col">
        <label class="inline-flex items-center gap-2">
            <input
                :id="id"
                :name="name"
                type="checkbox"
                :checked="modelValue"
                class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-800"
                @change="updateValue"
            />
            <span v-if="label" class="text-neutral-700 dark:text-neutral-300">{{ label }}</span>
        </label>
        <span v-if="firstError" class="mt-1 text-sm text-red-500 dark:text-red-400">
            {{ firstError }}
        </span>
    </div>
</template>