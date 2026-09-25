<script setup lang="ts">
interface Column {
    key: string;
    label: string;
    sortable?: boolean;
}

interface Props {
    columns: Column[];
    sort?: string | null;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    (e: 'sort', key: string): void;
}>();

function isActive(key: string) {
    return props.sort === key || props.sort === `-${key}`;
}

function direction(key: string) {
    return props.sort === `-${key}` ? 'desc' : 'asc';
}
</script>

<template>
    <table class="w-full border-collapse text-left">
        <thead>
            <tr class="border-b border-neutral-200 dark:border-neutral-700">
                <th
                    v-for="column in columns"
                    :key="column.key"
                    class="px-3 py-2 text-sm font-medium text-neutral-600 dark:text-neutral-300"
                    :class="{ 'cursor-pointer select-none': column.sortable }"
                    @click="column.sortable && emit('sort', column.key)"
                >
                    <span class="inline-flex items-center gap-1">
                        {{ column.label }}
                        <span v-if="column.sortable && isActive(column.key)" class="text-xs">
                            {{ direction(column.key) === 'asc' ? '▲' : '▼' }}
                        </span>
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            <slot />
        </tbody>
    </table>
</template>