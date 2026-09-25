<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import BaseBadge from '@/components/BaseBadge.vue';
import BasePagination from '@/components/BasePagination.vue';
import BaseTable from '@/components/BaseTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

interface StockMovement {
    id: string;
    quantity_change: number;
    reason: string;
    created_at: string;
}

interface Product {
    id: string;
    name: string;
    sku: string;
    quantity: number;
    reorder_threshold: number;
    is_below_threshold: boolean;
}

interface Props {
    product: {
        data: Product;
    };
    movements: {
        data: StockMovement[];
        links: ({ url: string | null; label: string; active: boolean } | null)[];
    };
}

defineProps<Props>();

function navigate(url: string) {
    router.get(
        url,
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

const columns = [
    { key: 'created_at', label: 'Date' },
    { key: 'quantity_change', label: 'Change' },
    { key: 'reason', label: 'Reason' },
];
</script>

<template>
    <div class="mx-auto max-w-3xl p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">{{ product.data.name }}</h1>
                <p class="text-sm text-neutral-500">{{ product.data.sku }}</p>
            </div>
            <BaseBadge v-if="product.data.is_below_threshold" variant="danger">
                Below threshold
            </BaseBadge>
        </div>

        <div class="mb-8 grid grid-cols-2 gap-4">
            <div class="rounded-md border border-neutral-200 p-4 dark:border-neutral-700">
                <p class="text-sm text-neutral-500">Current quantity</p>
                <p class="text-2xl font-semibold">{{ product.data.quantity }}</p>
            </div>
            <div class="rounded-md border border-neutral-200 p-4 dark:border-neutral-700">
                <p class="text-sm text-neutral-500">Reorder threshold</p>
                <p class="text-2xl font-semibold">{{ product.data.reorder_threshold }}</p>
            </div>
        </div>

        <h2 class="mb-3 text-lg font-medium">Stock movements</h2>

        <BaseTable :columns="columns">
            <tr
                v-for="movement in movements.data"
                :key="movement.id"
                class="border-b border-neutral-100 dark:border-neutral-800"
            >
                <td class="px-3 py-2 text-neutral-500">
                    {{ new Date(movement.created_at).toLocaleString() }}
                </td>
                <td
                    class="px-3 py-2 font-medium"
                    :class="movement.quantity_change > 0 ? 'text-green-600' : 'text-red-600'"
                >
                    {{ movement.quantity_change > 0 ? '+' : '' }}{{ movement.quantity_change }}
                </td>
                <td class="px-3 py-2">{{ movement.reason }}</td>
            </tr>
            <tr v-if="!movements.data.length">
                <td colspan="3" class="px-3 py-6 text-center text-neutral-400">
                    No movements recorded yet.
                </td>
            </tr>
        </BaseTable>

        <div class="mt-4">
            <BasePagination :links="movements.links" @navigate="navigate" />
        </div>
    </div>
</template>