<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

const props = defineProps({
    messages: {
        type: Array,
        required: true,
    },
    typingUsers: {
        type: Array,
        default: () => [],
    },
    conversation: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['markRead']);

const page = usePage();
const authUser = computed(() => page.props.auth.user);
const messagesContainer = ref(null);
const observer = ref(null);

const scrollToBottom = () => {
    nextTick(() => {
        if (messagesContainer.value) {
            messagesContainer.value.scrollTop =
                messagesContainer.value.scrollHeight;
        }
    });
};

const isMyMessage = (message) => {
    return message.user_id === authUser.value?.id;
};

const formatTime = (timestamp) => {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
    });
};

const formatDate = (timestamp) => {
    const date = new Date(timestamp);
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);

    if (date.toDateString() === today.toDateString()) {
        return 'Today';
    } else if (date.toDateString() === yesterday.toDateString()) {
        return 'Yesterday';
    } else {
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        });
    }
};

const shouldShowDateDivider = (message, index) => {
    if (index === 0) return true;

    const prevMessage = props.messages[index - 1];
    const currentDate = new Date(message.created_at).toDateString();
    const prevDate = new Date(prevMessage.created_at).toDateString();

    return currentDate !== prevDate;
};

const getReadByUsers = (message) => {
    if (!message.reads || !props.conversation.participants) return [];

    return message.reads
        .filter((read) => read.id !== authUser.value?.id)
        .map((read) => {
            const user = props.conversation.participants.find(
                (p) => p.id === read.id,
            );
            return user?.name;
        })
        .filter(Boolean);
};

const isMessageRead = (message) => {
    if (isMyMessage(message)) {
        return getReadByUsers(message).length > 0;
    }
    return false;
};

const getUserName = (user) => {
    if (!user) return 'Unknown';
    return user.name || `${user.first_name || ''} ${user.last_name || ''}`.trim() || 'Unknown';
};

const setupIntersectionObserver = () => {
    observer.value = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const messageId = entry.target.dataset.messageId;
                    const message = props.messages.find(
                        (m) => m.id === parseInt(messageId),
                    );

                    if (message && !isMyMessage(message)) {
                        // Check if not already read
                        const alreadyRead = message.reads?.some(
                            (r) => r.id === authUser.value?.id,
                        );
                        if (!alreadyRead) {
                            emit('markRead', message.id);
                        }
                    }
                }
            });
        },
        {
            root: messagesContainer.value,
            threshold: 0.5,
        },
    );
};

watch(
    () => props.messages.length,
    () => {
        scrollToBottom();

        // Observe new messages for read receipts
        nextTick(() => {
            const messageElements =
                messagesContainer.value?.querySelectorAll('[data-message-id]');
            messageElements?.forEach((el) => {
                observer.value?.observe(el);
            });
        });
    },
);

onMounted(() => {
    scrollToBottom();
    setupIntersectionObserver();

    // Observe initial messages
    nextTick(() => {
        const messageElements =
            messagesContainer.value?.querySelectorAll('[data-message-id]');
        messageElements?.forEach((el) => {
            observer.value?.observe(el);
        });
    });
});
</script>

<template>
    <div
        ref="messagesContainer"
        class="flex-1 space-y-4 overflow-y-auto bg-gray-50 p-4"
    >
        <template v-for="(message, index) in messages" :key="message.id">
            <!-- Date Divider -->
            <div
                v-if="shouldShowDateDivider(message, index)"
                class="my-4 flex items-center justify-center"
            >
                <div
                    class="rounded-full bg-gray-200 px-3 py-1 text-xs text-gray-600"
                >
                    {{ formatDate(message.created_at) }}
                </div>
            </div>

            <!-- Message -->
            <div
                :class="[
                    'flex',
                    isMyMessage(message) ? 'justify-end' : 'justify-start',
                ]"
                :data-message-id="message.id"
            >
                <div
                    :class="[
                        'flex max-w-[75%] gap-2',
                        isMyMessage(message) ? 'flex-row-reverse' : 'flex-row',
                    ]"
                >
                    <!-- Avatar (only for other users) -->
                    <div v-if="!isMyMessage(message)" class="flex-shrink-0">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-gray-400 to-gray-600 text-sm font-semibold text-white"
                        >
                            {{ getUserName(message.user).charAt(0).toUpperCase() }}
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div>
                        <!-- Sender name (only for other users) -->
                        <div
                            v-if="!isMyMessage(message)"
                            class="mb-1 px-1 text-xs text-gray-600"
                        >
                            {{ getUserName(message.user) }}
                        </div>

                        <!-- Message bubble -->
                        <div
                            :class="[
                                'rounded-lg px-4 py-2 break-words',
                                isMyMessage(message)
                                    ? 'bg-blue-600 text-white'
                                    : 'border border-gray-200 bg-white text-gray-900',
                            ]"
                        >
                            <!-- Attachment -->
                            <div v-if="message.attachment_path" class="mb-2">
                                <a
                                    :href="`/storage/${message.attachment_path}`"
                                    target="_blank"
                                    :class="[
                                        'flex items-center gap-2 p-2 rounded',
                                        isMyMessage(message) ? 'bg-blue-700' : 'bg-gray-100'
                                    ]"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                                        />
                                    </svg>
                                    <span class="truncate text-sm">{{
                                        message.attachment_name
                                    }}</span>
                                </a>
                            </div>

                            <!-- Message text -->
                            <p v-if="message.body" class="whitespace-pre-wrap">
                                {{ message.body }}
                            </p>
                        </div>

                        <!-- Message metadata -->
                        <div
                            :class="[
                                'mt-1 flex items-center gap-2 px-1 text-xs',
                                isMyMessage(message)
                                    ? 'justify-end'
                                    : 'justify-start',
                            ]"
                        >
                            <span class="text-gray-500">{{ formatTime(message.created_at) }}</span>

                            <!-- Read receipts (only for my messages) -->
                            <div v-if="isMyMessage(message)" class="flex items-center gap-1">
                                <svg
                                    v-if="isMessageRead(message)"
                                    class="h-3 w-3 text-blue-500"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Typing Indicator -->
        <div v-if="typingUsers.length > 0" class="flex justify-start">
            <div class="flex max-w-[75%] gap-2">
                <div class="flex-shrink-0">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-gray-400 to-gray-600 text-sm font-semibold text-white"
                    >
                        {{ typingUsers[0].name.charAt(0).toUpperCase() }}
                    </div>
                </div>
                <div>
                    <div class="mb-1 px-1 text-xs text-gray-600">
                        {{ typingUsers[0].name }}
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-2">
                        <div class="flex gap-1">
                            <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay: 0ms"></span>
                            <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay: 150ms"></span>
                            <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
