<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  conversations: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['select'])

const page = usePage()
const authUser = computed(() => page.props.auth.user)

const getConversationName = (conversation) => {
  if (conversation.name) return conversation.name
  
  if (conversation.type === 'direct' && conversation.participants) {
    const otherUser = conversation.participants.find(p => p.id !== authUser.value?.id)
    return otherUser?.name || 'Unknown User'
  }
  
  return 'Conversation'
}

const formatTime = (timestamp) => {
  if (!timestamp) return ''
  
  const date = new Date(timestamp)
  const now = new Date()
  const diff = now - date
  
  // Less than 1 day
  if (diff < 86400000) {
    return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })
  }
  
  // Less than 7 days
  if (diff < 604800000) {
    return date.toLocaleDateString('en-US', { weekday: 'short' })
  }
  
  // Older
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}


  


const getLastMessage = (conversation) => {
  const latest = conversation.latest_message?.[0]
  if (!latest) return 'No messages yet'
  
  let text = latest.body || ''
  if (latest.attachment_name) {
    text = `📎 ${latest.attachment_name}`
  }
  
  return text.length > 50 ? text.substring(0, 50) + '...' : text
}
</script>

<template>
  <div class="flex-1 overflow-y-auto">
    <div v-if="conversations.length === 0" class="flex items-center justify-center h-full text-gray-500">
      <div class="text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <p class="text-sm">No conversations yet</p>
        <p class="text-xs mt-1">Start a new conversation</p>
      </div>
    </div>

    <div v-else class="divide-y">
      <button
        v-for="conversation in conversations"
        :key="conversation.id"
        @click="emit('select', conversation)"
        class="w-full p-4 hover:bg-gray-50 transition-colors flex items-start gap-3 text-left"
      >
        <!-- Avatar -->
        <div class="flex-shrink-0">
          <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">
            {{ getConversationName(conversation).charAt(0).toUpperCase() }}
          </div>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between mb-1">
            <h3 class="font-semibold text-gray-900 truncate">
              {{ getConversationName(conversation) }}
            </h3>
            <span class="text-xs text-gray-500 ml-2 flex-shrink-0">
              {{ formatTime(conversation.latest_message?.[0]?.created_at) }}
            </span>
          </div>
          
          <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600 truncate">
              {{ getLastMessage(conversation) }}
            </p>
            
            <!-- Unread badge -->
            <span
              v-if="conversation.unread_count > 0"
              class="ml-2 flex-shrink-0 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full min-w-[1.5rem] text-center"
            >
              {{ conversation.unread_count > 99 ? '99+' : conversation.unread_count }}
            </span>
          </div>
        </div>
      </button>
    </div>
  </div>
</template>