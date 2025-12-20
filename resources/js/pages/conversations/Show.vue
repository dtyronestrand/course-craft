<template>
    <ChatLayout>
        <template v-slot:sidebar>
            <Sidebar />
        </template>
        <div
            class="flex grow flex-col overflow-hidden px-4 py-6 sm:px-6 lg:pl-8 xl:pl-6"
        >
            <div class="grow space-y-5 overflow-auto pb-24">
                <Messages v-if="props.messages?.length">
                    <Message v-for="message in props.messages" :key="message.id" :message="message" />
                </Messages>
                <div v-else>No messages.</div>
            </div>
            <SendMessage :conversation="props.conversation" />
        </div>
    </ChatLayout>
</template>

<script setup lang="ts">
import Message from '@/components/Conversations/Message.vue';
import Messages from '@/components/Conversations/Messages.vue';
import SendMessage from '@/components/Conversations/SendMessage.vue';
import Sidebar from '@/components/Sidebar/Sidebar.vue';
import ChatLayout from '@/layouts/ChatLayout.vue';

const props = defineProps<{
    conversation: any;
    messages: any;
}>();
</script>

<style scoped></style>
