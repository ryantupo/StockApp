<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import axios from 'axios';
import { router, usePage } from '@inertiajs/vue3';
import notifications from '@/routes/notifications';
import productRoutes from '@/routes/products';
import BaseButton from '@/components/BaseButton.vue';

interface Notification {
    id: string;
    type: string | null;
    data: Record<string, unknown>;
    read_at: string | null;
    created_at: string;
}

interface SharedNotifications {
    unread_count: number;
    recent: { data: Notification[] };
}

const page = usePage<{ notifications: SharedNotifications | null }>();

const open = ref(false);

const unreadCount = computed(() => page.props.notifications?.unread_count ?? 0);
const recent = computed(() => page.props.notifications?.recent.data ?? []);
const hasRead = computed(() => recent.value.some((n) => n.read_at));

async function handleClick(notification: Notification) {
    await axios.patch(notifications.update.url(notification.id));

    open.value = false;

    if (notification.type === 'low_stock_alert' && notification.data.product_id) {
        router.visit(productRoutes.show.url(notification.data.product_id as string));
    } else {
        router.reload({ only: ['notifications'] });
    }
}

async function markAsRead(notification: Notification, event: Event) {
    event.stopPropagation();

    await axios.patch(notifications.update.url(notification.id));

    router.reload({ only: ['notifications'] });
}

async function clearRead() {
    await axios.delete(notifications.clear.url());

    router.reload({ only: ['notifications'] });
}

function summaryFor(notification: Notification) {
    if (notification.type === 'low_stock_alert') {
        return `${notification.data.product_name} is below threshold`;
    }
    if (notification.type === 'low_stock_digest') {
        return `${notification.data.product_count} products below threshold`;
    }
    return 'Notification';
}

let pollInterval: ReturnType<typeof setInterval> | undefined;

onMounted(() => {
    pollInterval = setInterval(() => {
        router.reload({ only: ['notifications'] });
    }, 15000);
});

onUnmounted(() => {
    clearInterval(pollInterval);
});
</script>

<template>
    <div class="relative">
        <button
            class="relative rounded-full p-2 hover:bg-neutral-100 dark:hover:bg-neutral-800"
            @click="open = !open"
        >
            🔔
            <span
                v-if="unreadCount > 0"
                class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] text-white"
            >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <div
            v-if="open"
            class="absolute right-0 z-20 mt-2 w-80 rounded-md border border-neutral-200 bg-white shadow-lg dark:border-neutral-700 dark:bg-neutral-800"
        >
            <div class="flex items-center justify-between border-b border-neutral-100 px-4 py-2 dark:border-neutral-700">
                <span class="text-sm font-medium">Notifications</span>
                <BaseButton v-if="hasRead" variant="secondary" size="sm" @click="clearRead">
                    Clear read
                </BaseButton>
            </div>
            <ul class="max-h-96 overflow-auto">
                <li
                    v-for="notification in recent"
                    :key="notification.id"
                    class="group flex items-start justify-between gap-2 border-b border-neutral-100 px-4 py-3 text-sm last:border-0 hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-700"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/20': !notification.read_at }"
                >
                    <div class="flex-1 cursor-pointer" @click="handleClick(notification)">
                        <p>{{ summaryFor(notification) }}</p>
                        <p class="mt-1 text-xs text-neutral-400">
                            {{ new Date(notification.created_at).toLocaleString() }}
                        </p>
                    </div>
                    <BaseButton
                        v-if="!notification.read_at"
                        variant="secondary"
                        size="sm"
                        class="shrink-0 opacity-0 group-hover:opacity-100"
                        @click="markAsRead(notification, $event)"
                    >
                        Mark read
                    </BaseButton>
                </li>
                <li v-if="!recent.length" class="px-4 py-6 text-center text-sm text-neutral-400">
                    No notifications
                </li>
            </ul>
        </div>
    </div>
</template>