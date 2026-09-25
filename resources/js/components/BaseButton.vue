<script setup lang="ts">
interface Props {
    type?: 'button' | 'submit' | 'reset';
    variant?: 'primary' | 'secondary' | 'danger';
    size?: 'sm' | 'md';
    disabled?: boolean;
}

withDefaults(defineProps<Props>(), {
    type: 'button',
    variant: 'primary',
    size: 'md',
    disabled: false,
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        class="rounded-md font-medium transition disabled:cursor-not-allowed disabled:opacity-50"
        :class="[
            size === 'sm' ? 'px-2.5 py-1 text-xs' : 'px-4 py-2 text-sm',
            {
                'bg-neutral-900 text-white hover:bg-neutral-700 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-300':
                    variant === 'primary',
                'bg-neutral-100 text-neutral-900 hover:bg-neutral-200 dark:bg-neutral-800 dark:text-neutral-100 dark:hover:bg-neutral-700':
                    variant === 'secondary',
                'bg-red-600 text-white hover:bg-red-500': variant === 'danger',
            },
        ]"
    >
        <slot />
    </button>
</template>