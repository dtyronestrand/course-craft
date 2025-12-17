<script setup>
import { useChat } from '@/composables/useChat';
import { useNotifications } from '@/composables/useNotifications';
import { computed, ref } from 'vue';
import ConversationList from './ConversationList.vue';
import MessageDisplay from './MessageDisplay.vue';
import MessageInput from './MessageInput.vue';
import NewConversationModal from './NewConversationModal.vue';

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);

const {
    conversations,
    activeConversation,
    messages,
    typingUsers,
    fetchConversations,
    sendMessage,
    markAsRead,
    sendTypingIndicator,
    setActiveConversation,
    createConversation,
} = useChat();

const { unreadCount } = useNotifications();

const showNewConversation = ref(false);
const isLoading = ref(false);

const totalUnreadCount = computed(() => {
    return conversations.value.reduce(
        (sum, conv) => sum + (conv.unread_count || 0),
        0,
    );
});

const init = async () => {
    await fetchConversations();
};

const handleSelectConversation = async (conversation) => {
    await setActiveConversation(conversation);
};

const handleSendMessage = async (body, attachment) => {
    if (!activeConversation.value) return;
    await sendMessage(activeConversation.value.id, body, attachment);
};

const handleTyping = () => {
    if (!activeConversation.value) return;
    sendTypingIndicator(activeConversation.value.id);
};

const handleBack = () => {
    activeConversation.value = null;
};

const handleCreateConversation = async (data) => {
    isLoading.value = true;
    try {
        const conversation = await createConversation(data);
        showNewConversation.value = false;
        await setActiveConversation(conversation);
    } catch (error) {
        console.error('Failed to create conversation:', error);
        alert('Failed to create conversation. Please try again.');
    } finally {
        isLoading.value = false;
    }
};

init();
</script>

<template>
    <!-- Overlay -->
    <div
        v-if="open"
        class="bg-opacity-50 fixed inset-0 z-40 bg-black transition-opacity"
        @click="emit('close')"
    ></div>

    <!-- Sidebar -->
    <div
        :class="[
            'fixed top-0 right-0 z-50 flex h-full w-full transform flex-col bg-white shadow-2xl transition-transform duration-300 ease-in-out md:w-96',
            open ? 'translate-x-0' : 'translate-x-full',
        ]"
    >
        <!-- Header -->
        <div class="flex items-center justify-between border-b bg-gray-50 p-4">
            <button
                v-if="activeConversation"
                @click="handleBack"
                class="rounded-lg p-2 transition-colors hover:bg-gray-200"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </button>

            <h2 class="flex-1 text-lg font-semibold">
                {{
                    activeConversation
                        ? activeConversation.name || 'Direct Message'
                        : 'Messages'
                }}
            </h2>

            <!-- Unread badge -->
            <div
                v-if="!activeConversation && totalUnreadCount > 0"
                class="mr-2"
            >
                <span
                    class="rounded-full bg-red-600 px-2 py-1 text-xs font-semibold text-white"
                >
                    {{ totalUnreadCount > 99 ? '99+' : totalUnreadCount }}
                </span>
            </div>

            <button
                v-if="!activeConversation"
                @click="showNewConversation = true"
                class="rounded-lg p-2 transition-colors hover:bg-gray-200"
                title="New Conversation"
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
                        d="M12 4v16m8-8H4"
                    />
                </svg>
            </button>

            <button
                @click="emit('close')"
                class="ml-2 rounded-lg p-2 transition-colors hover:bg-gray-200"
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
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Conversation List -->
            <ConversationList
                v-if="!activeConversation"
                :conversations="conversations"
                @select="handleSelectConversation"
            />

            <!-- Active Conversation -->
            <template v-else>
                <MessageDisplay
                    :messages="messages"
                    :typing-users="typingUsers"
                    :conversation="activeConversation"
                    @mark-read="markAsRead"
                />

                <MessageInput
                    @send="handleSendMessage"
                    @typing="handleTyping"
                />
            </template>
        </div>
    </div>

    <!-- New Conversation Modal -->
    <NewConversationModal
        :open="showNewConversation"
        @close="showNewConversation = false"
        @created="handleCreateConversation"
    />
</template>
