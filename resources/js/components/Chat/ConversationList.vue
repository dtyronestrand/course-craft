<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    conversations: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['select']);

const page = usePage();
const authUser = computed(() => page.props.auth.user);

const getConversationName = (conversation) => {
    if (conversation.name) return conversation.name;

    if (conversation.type === 'direct' && conversation.participants) {
        const otherUser = conversation.participants.find(
            (p) => p.id !== authUser.value?.id,
        );
        return otherUser?.first_name || 'Unknown User';
    }

    return 'Conversation';
};

const formatTime = (timestamp) => {
    if (!timestamp) return '';

    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;

    // Less than 1 day
    if (diff < 86400000) {
        return date.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
        });
    }

    // Less than 7 days
    if (diff < 604800000) {
        return date.toLocaleDateString('en-US', { weekday: 'short' });
    }

    // Older
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};

const getLastMessage = (conversation) => {
    const latest = conversation.latest_message?.[0];
    if (!latest) return 'No messages yet';

    let text = latest.body || '';
    if (latest.attachment_name) {
        text = `📎 ${latest.attachment_name}`;
    }

    return text.length > 50 ? text.substring(0, 50) + '...' : text;
};
</script>

<template>
    <div class="flex-1 overflow-y-auto">
        <div
            v-if="conversations.length === 0"
            class="flex h-full items-center justify-center text-gray-500"
        >
            <div class="text-center">
                <svg
                    class="mx-auto mb-4 h-16 w-16 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                    />
                </svg>
                <p class="text-sm">No conversations yet</p>
                <p class="mt-1 text-xs">Start a new conversation</p>
            </div>
        </div>

        <div v-else class="divide-y">
            <button
                v-for="conversation in conversations"
                :key="conversation.id"
                @click="emit('select', conversation)"
                class="flex w-full items-start gap-3 p-4 text-left transition-colors hover:bg-gray-50"
            >
                <!-- Avatar -->
                <div class="shrink-0">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-linear-to-br from-blue-400 to-blue-600 font-semibold text-white"
                    >
                        {{
                            getConversationName(conversation)
                                .charAt(0)
                                .toUpperCase()
                        }}
                    </div>
                </div>

                <!-- Content -->
                <div class="min-w-0 flex-1">
                    <div class="mb-1 flex items-center justify-between">
                        <h3 class="truncate font-semibold text-gray-900">
                            {{ getConversationName(conversation) }}
                        </h3>
                        <span class="ml-2 shrink-0 text-xs text-gray-500">
                            {{
                                formatTime(
                                    conversation.latest_message?.[0]
                                        ?.created_at,
                                )
                            }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="truncate text-sm text-gray-600">
                            {{ getLastMessage(conversation) }}
                        </p>

                        <!-- Unread badge -->
                        <span
                            v-if="conversation.unread_count > 0"
                            class="ml-2 min-w-6 shrink-0 rounded-full bg-blue-600 px-2 py-1 text-center text-xs font-semibold text-white"
                        >
                            {{
                                conversation.unread_count > 99
                                    ? '99+'
                                    : conversation.unread_count
                            }}
                        </span>
                    </div>
                </div>
            </button>
        </div>
    </div>
</template>
