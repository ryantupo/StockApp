<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    modelValue: null | string | number | undefined;
    label?: string;
    name: string;
    type?: string;
    placeholder?: string;
    error?: string | string[];
    required?: boolean;
    min?: number | string;
    max?: number | string;
    minlength?: number;
    maxlength?: number;
    pattern?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
});
const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number): void;
}>();

const id = computed(() => props.name);

function updateValue(event: Event) {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', target.value);
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
        <label
            v-if="label"
            :for="id"
            class="mb-1 font-medium text-neutral-700 dark:text-neutral-300"
        >
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <input
            :id="id"
            :name="name"
            :type="type"
            :placeholder="placeholder"
            :value="modelValue"
            :required="required"
            :min="min"
            :max="max"
            :minlength="minlength"
            :maxlength="maxlength"
            :pattern="pattern"
            class="rounded-md border border-neutral-300 p-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200"
            @input="updateValue"
        />
        <span v-if="firstError" class="mt-1 text-sm text-red-500 dark:text-red-400">
            {{ firstError }}
        </span>
    </div>
</template>