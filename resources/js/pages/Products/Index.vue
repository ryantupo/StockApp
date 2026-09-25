<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

import BaseButton from '@/components/BaseButton.vue';
import BaseBadge from '@/components/BaseBadge.vue';
import BaseField from '@/components/BaseField.vue';
import BaseCheckbox from '@/components/BaseCheckbox.vue';
import BasePagination from '@/components/BasePagination.vue';
import BaseTable from '@/components/BaseTable.vue';
import RecordStockMovementDialog from '@/components/dialogs/RecordStockMovementDialog.vue';
import productRoutes from '@/routes/products';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

interface Product {
    id: string;
    name: string;
    sku: string;
    quantity: number;
    reorder_threshold: number;
    is_below_threshold: boolean;
}

interface Props {
    products: {
        data: Product[];
        links: ({ url: string | null; label: string; active: boolean } | null)[];
    };
    filters: {
        filter?: {
            search?: string;
            below_threshold?: boolean;
        };
        sort?: string;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters.filter?.search ?? '');
const belowThreshold = ref(props.filters.filter?.below_threshold ?? false);

const movementDialogOpen = ref(false);
const selectedProduct = ref<Product | null>(null);

function openMovementDialog(product: Product) {
    selectedProduct.value = product;
    movementDialogOpen.value = true;
}

function applyFilters() {
    router.get(
        productRoutes.index.url(),
        {
            filter: {
                search: search.value || undefined,
                below_threshold: belowThreshold.value || undefined,
            },
            sort: props.filters.sort,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}

let debounceTimer: ReturnType<typeof setTimeout>;

watch(search, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(applyFilters, 300);
});

watch(belowThreshold, applyFilters);

function sort(key: string) {
    const current = props.filters.sort;
    const next = current === key ? `-${key}` : key;

    router.get(
        productRoutes.index.url(),
        {
            filter: props.filters.filter,
            sort: next,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}

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
    { key: 'name', label: 'Name', sortable: true },
    { key: 'sku', label: 'SKU' },
    { key: 'quantity', label: 'Quantity', sortable: true },
    { key: 'reorder_threshold', label: 'Threshold' },
    { key: 'actions', label: '' },
];
</script>

<template>
    <div class="mx-auto max-w-5xl p-6">
        <h1 class="mb-4 text-xl font-semibold">
            Products
        </h1>

        <div class="mb-4 flex items-end gap-4">
            <BaseField
                v-model="search"
                name="search"
                label="Search"
                placeholder="Name or SKU..."
            />

            <BaseCheckbox
                v-model="belowThreshold"
                name="below_threshold"
                label="Below threshold only"
            />
        </div>

        <BaseTable
            :columns="columns"
            :sort="filters.sort"
            @sort="sort"
        >
            <tr
                v-for="product in products.data"
                :key="product.id"
                class="border-b border-neutral-100 dark:border-neutral-800"
            >
                <td class="px-3 py-2">
                    <a
                        :href="productRoutes.show.url(product.id)"
                        class="hover:underline"
                    >
                        {{ product.name }}
                    </a>
                </td>

                <td class="px-3 py-2 text-neutral-500">
                    {{ product.sku }}
                </td>

                <td class="px-3 py-2">
                    {{ product.quantity }}

                    <BaseBadge
                        v-if="product.is_below_threshold"
                        variant="danger"
                        class="ml-2"
                    >
                        Low stock
                    </BaseBadge>
                </td>

                <td class="px-3 py-2 text-neutral-500">
                    {{ product.reorder_threshold }}
                </td>

                <td class="px-3 py-2">
                    <BaseButton variant="secondary" size="sm" @click="openMovementDialog(product)">
                        Record movement
                    </BaseButton>
                </td>
            </tr>
        </BaseTable>

        <div class="mt-4">
            <BasePagination
                :links="products.links"
                @navigate="navigate"
            />
        </div>

        <RecordStockMovementDialog
            v-if="selectedProduct"
            v-model="movementDialogOpen"
            :product="selectedProduct"
        />
    </div>
</template>