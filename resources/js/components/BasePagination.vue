<script setup lang="ts">
interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Props {
    links: (PaginationLink | null)[];
}

defineProps<Props>();

const emit = defineEmits<{
    (e: 'navigate', url: string): void;
}>();
</script>

<template>
    <nav class="flex items-center gap-1">
        <button
            v-for="(link, i) in links"
            :key="i"
            :disabled="!link?.url"
            :class="[
                'rounded px-3 py-1 text-sm',
                link?.active
                    ? 'bg-neutral-900 text-white dark:bg-neutral-100 dark:text-neutral-900'
                    : 'hover:bg-neutral-100 dark:hover:bg-neutral-800',
                !link?.url && 'cursor-not-allowed opacity-40',
            ]"
            v-html="link?.label ?? ''"
            @click="link?.url && emit('navigate', link.url)"
        />
    </nav>
</template>