<script setup lang="ts">
interface Props {
    variant?: 'default' | 'danger' | 'success';
}

withDefaults(defineProps<Props>(), { variant: 'default' });
</script>

<template>
    <span
        class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
        :class="{
            'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300': variant === 'default',
            'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300': variant === 'danger',
            'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300': variant === 'success',
        }"
    >
        <slot />
    </span>
</template>