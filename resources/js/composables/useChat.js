import axios from 'axios';
import { onUnmounted, ref } from 'vue';

export function useChat() {
    const conversations = ref([]);
    const activeConversation = ref(null);
    const messages = ref([]);
    const typingUsers = ref([]);
    const channels = ref({});
    const typingTimeout = ref(null);
    const lastTypingTime = ref(0);

    const fetchConversations = async () => {
        const response = await axios.get('/conversations');
        conversations.value = response.data;
    };

    const fetchMessages = async (conversationId) => {
        const response = await axios.get(`/conversations/${conversationId}`);
        const msgs = response.data.messages?.data || [];
        messages.value = [...msgs].reverse();
        return response.data;
    };

    const sendMessage = async (conversationId, body, attachment = null) => {
        const formData = new FormData();
        if (body) formData.append('body', body);
        if (attachment) formData.append('attachment', attachment);

        const response = await axios.post(
            `/conversations/${conversationId}/messages`,
            formData,
            {
                headers: { 'Content-Type': 'multipart/form-data' },
            },
        );

        messages.value.push(response.data);
        return response.data;
    };

    const markAsRead = async (messageId) => {
        await axios.post(`/messages/${messageId}/read`);
    };

    const sendTypingIndicator = async (conversationId) => {
        const now = Date.now();

        // Only send typing indicator every 2 seconds
        if (now - lastTypingTime.value < 2000) return;

        lastTypingTime.value = now;
        await axios.post(`/conversations/${conversationId}/typing`);
    };

    const joinConversation = (conversationId) => {
        if (channels.value[conversationId]) return;

        const channel = window.Echo.join(`conversation.${conversationId}`)
            .here((users) => {
                console.log('Users in conversation:', users);
            })
            .joining((user) => {
                console.log('User joined:', user.name);
            })
            .leaving((user) => {
                console.log('User left:', user.name);
            })
            .listen('MessageSent', (e) => {
                messages.value.push(e.message);
                updateConversationsList(conversationId, e.message);
            })
            .listen('UserTyping', (e) => {
                // Remove existing typing indicator for this user
                typingUsers.value = typingUsers.value.filter(
                    (u) => u.id !== e.user.id,
                );

                // Add new typing indicator
                typingUsers.value.push(e.user);

                // Auto-remove after 3 seconds
                setTimeout(() => {
                    typingUsers.value = typingUsers.value.filter(
                        (u) => u.id !== e.user.id,
                    );
                }, 3000);
            })
            .listen('MessageRead', (e) => {
                const message = messages.value.find(
                    (m) => m.id === e.message_id,
                );
                if (message) {
                    if (!message.reads) message.reads = [];
                    if (!message.reads.find((r) => r.id === e.user_id)) {
                        message.reads.push({
                            id: e.user_id,
                            pivot: { read_at: e.read_at },
                        });
                    }
                }
            });

        channels.value[conversationId] = channel;
    };

    const leaveConversation = (conversationId) => {
        if (channels.value[conversationId]) {
            window.Echo.leave(`conversation.${conversationId}`);
            delete channels.value[conversationId];
            typingUsers.value = [];
        }
    };

    const updateConversationsList = (conversationId, latestMessage) => {
        const conv = conversations.value.find((c) => c.id === conversationId);
        if (conv) {
            conv.latest_message = [latestMessage];
            conv.unread_count = (conv.unread_count || 0) + 1;

            // Move to top
            conversations.value = [
                conv,
                ...conversations.value.filter((c) => c.id !== conversationId),
            ];
        } else {
            // If conversation not in list, refetch
            fetchConversations();
        }
    };

    const createConversation = async (data) => {
        const response = await axios.post('/conversations', data);
        conversations.value.unshift(response.data);
        return response.data;
    };

    const setActiveConversation = async (conversation) => {
        if (activeConversation.value?.id) {
            leaveConversation(activeConversation.value.id);
        }

        activeConversation.value = conversation;
        typingUsers.value = [];

        await fetchMessages(conversation.id);
        joinConversation(conversation.id);

        // Reset unread count locally
        const conv = conversations.value.find((c) => c.id === conversation.id);
        if (conv) {
            conv.unread_count = 0;
        }
    };

    onUnmounted(() => {
        Object.keys(channels.value).forEach((conversationId) => {
            leaveConversation(conversationId);
        });
    });

    return {
        conversations,
        activeConversation,
        messages,
        typingUsers,
        fetchConversations,
        fetchMessages,
        sendMessage,
        markAsRead,
        sendTypingIndicator,
        joinConversation,
        leaveConversation,
        setActiveConversation,
        createConversation,
    };
}
