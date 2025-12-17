<script setup>
import { ref, computed } from 'vue'
import { useChat } from '@/composables/useChat'
import { useNotifications } from '@/composables/useNotifications'
import ConversationList from './ConversationList.vue'
import MessageDisplay from './MessageDisplay.vue'
import MessageInput from './MessageInput.vue'

const props = defineProps({
  open: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

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
  createConversation
} = useChat()

const { unreadCount, markAsRead: markNotificationAsRead } = useNotifications()

const showNewConversation = ref(false)

const totalUnreadCount = computed(() => {
  return conversations.value.reduce((sum, conv) => sum + (conv.unread_count || 0), 0)
})

const init = async () => {
  await fetchConversations()
}

const handleSelectConversation = async (conversation) => {
  await setActiveConversation(conversation)
}

const handleSendMessage = async (body, attachment) => {
  if (!activeConversation.value) return
  await sendMessage(activeConversation.value.id, body, attachment)
}

const handleTyping = () => {
  if (!activeConversation.value) return
  sendTypingIndicator(activeConversation.value.id)
}

const handleBack = () => {
  activeConversation.value = null
}

init()
</script>

<template>
  <!-- Overlay -->
  <div 
    v-if="open"
    class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity"
    @click="emit('close')"
  ></div>

  <!-- Sidebar -->
  <div 
    :class="[
      'fixed top-0 right-0 h-full w-full md:w-96 bg-white shadow-2xl z-50 transform transition-transform duration-300 ease-in-out flex flex-col',
      open ? 'translate-x-0' : 'translate-x-full'
    ]"
  >
    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b bg-gray-50">
      <button
        v-if="activeConversation"
        @click="handleBack"
        class="p-2 hover:bg-gray-200 rounded-lg transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      
      <h2 class="text-lg font-semibold flex-1">
        {{ activeConversation ? activeConversation.name || 'Direct Message' : 'Messages' }}
      </h2>

      <!-- Unread badge -->
      <div v-if="!activeConversation && totalUnreadCount > 0" class="mr-2">
        <span class="bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded-full">
          {{ totalUnreadCount > 99 ? '99+' : totalUnreadCount }}
        </span>
      </div>

      <button
        v-if="!activeConversation"
        @click="showNewConversation = true"
        class="p-2 hover:bg-gray-200 rounded-lg transition-colors"
        title="New Conversation"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
      </button>

      <button
        @click="emit('close')"
        class="p-2 hover:bg-gray-200 rounded-lg transition-colors ml-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-hidden flex flex-col">
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
</template>