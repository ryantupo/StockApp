<script setup lang="ts">
import { computed, ref } from 'vue';

interface Item {
    value: string | number;
    label: string;
}

interface Props {
    modelValue: (string | number)[] | string | number | null;
    items: Item[];
    label?: string;
    name: string;
    placeholder?: string;
    error?: string | string[];
    required?: boolean;
    multiple?: boolean;
    taggable?: boolean;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    (e: 'update:modelValue', value: (string | number)[] | string | number | null): void;
}>();

const query = ref('');
const open = ref(false);

const selected = computed<(string | number)[]>(() => {
    if (props.multiple) {
        return (props.modelValue as (string | number)[]) ?? [];
    }
    return props.modelValue ? [props.modelValue as string | number] : [];
});

const filtered = computed(() =>
    props.items.filter((item) => item.label.toLowerCase().includes(query.value.toLowerCase())),
);

function select(value: string | number) {
    if (props.multiple) {
        const next = selected.value.includes(value)
            ? selected.value.filter((v) => v !== value)
            : [...selected.value, value];
        emit('update:modelValue', next);
    } else {
        emit('update:modelValue', value);
        open.value = false;
    }
    query.value = '';
}

function remove(value: string | number) {
    emit(
        'update:modelValue',
        selected.value.filter((v) => v !== value),
    );
}

function handleEnter() {
    if (!props.taggable || !query.value.trim()) {
        return;
    }
    select(query.value.trim());
}

const firstError = computed(() => {
    if (!props.error) {
        return '';
    }
    return Array.isArray(props.error) ? props.error[0] : props.error;
});

function labelFor(value: string | number) {
    return props.items.find((i) => i.value === value)?.label ?? value;
}
</script>

<template>
    <div class="mb-4 flex flex-col">
        <label v-if="label" :for="name" class="mb-1 font-medium text-neutral-700 dark:text-neutral-300">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <div class="relative">
            <div
                class="flex min-h-10 flex-wrap items-center gap-1 rounded-md border border-neutral-300 p-1.5 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-800"
            >
                <span
                    v-for="value in selected"
                    :key="value"
                    class="inline-flex items-center gap-1 rounded bg-neutral-100 px-2 py-0.5 text-sm dark:bg-neutral-700"
                >
                    {{ labelFor(value) }}
                    <button type="button" class="text-neutral-400 hover:text-neutral-600" @click="remove(value)">
                        ✕
                    </button>
                </span>
                <input
                    :id="name"
                    v-model="query"
                    :placeholder="placeholder"
                    class="min-w-24 flex-1 bg-transparent p-1 outline-none dark:text-neutral-200"
                    @focus="open = true"
                    @keydown.enter.prevent="handleEnter"
                />
            </div>

            <ul
                v-if="open && filtered.length"
                class="absolute z-10 mt-1 max-h-48 w-full overflow-auto rounded-md border border-neutral-200 bg-white shadow-lg dark:border-neutral-700 dark:bg-neutral-800"
            >
                <li
                    v-for="item in filtered"
                    :key="item.value"
                    class="cursor-pointer px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-700"
                    @click="select(item.value)"
                >
                    {{ item.label }}
                </li>
            </ul>
        </div>

        <span v-if="firstError" class="mt-1 text-sm text-red-500 dark:text-red-400">
            {{ firstError }}
        </span>
    </div>
</template>