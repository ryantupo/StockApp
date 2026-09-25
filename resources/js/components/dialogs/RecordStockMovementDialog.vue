<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import BaseButton from '@/components/BaseButton.vue';
import BaseDialog from '@/components/BaseDialog.vue';
import BaseField from '@/components/BaseField.vue';
import movements from '@/routes/products/movements';

interface Product {
    id: string;
    name: string;
}

interface Props {
    modelValue: boolean;
    product: Product;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const form = useForm({
    quantity_change: null as number | null,
    reason: '',
});

function close() {
    emit('update:modelValue', false);
}

function handleSubmit() {
    if (form.processing) {
        return;
    }

    form.post(movements.create.url(props.product.id), {
        onSuccess: () => {
            form.reset();
            close();
        },
    });
}
</script>

<template>
    <BaseDialog
        :modal="true"
        :title="`Record movement: ${product.name}`"
        size="md"
        :model-value="modelValue"
        @update:model-value="emit('update:modelValue', $event)"
    >
        <form @submit.prevent="handleSubmit">
            <BaseField
                v-model="form.quantity_change"
                type="number"
                label="Quantity change"
                name="quantity_change"
                placeholder="e.g. 10 or -5"
                required
                :error="form.errors.quantity_change"
            />

            <BaseField
                v-model="form.reason"
                label="Reason"
                name="reason"
                placeholder="e.g. Restock delivery"
                required
                :error="form.errors.reason"
            />
        </form>

        <template #footer>
            <BaseButton
                type="button"
                variant="secondary"
                :disabled="form.processing"
                @click="close"
            >
                Cancel
            </BaseButton>

            <BaseButton
                type="button"
                :disabled="form.processing"
                @click="handleSubmit"
            >
                {{ form.processing ? 'Recording...' : 'Record movement' }}
            </BaseButton>
        </template>
    </BaseDialog>
</template>