<script setup>
import { ref, computed, watch, nextTick, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  messages: {
    type: Array,
    required: true
  },
  typingUsers: {
    type: Array,
    default: () => []
  },
  conversation: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['markRead'])

const page = usePage()
const authUser = computed(() => page.props.auth.user)
const messagesContainer = ref(null)
const observer = ref(null)

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

const isMyMessage = (message) => {
  return message.user_id === authUser.value?.id
}

const formatTime = (timestamp) => {
  const date = new Date(timestamp)
  return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })
}

const formatDate = (timestamp) => {
  const date = new Date(timestamp)
  const today = new Date()
  const yesterday = new Date(today)
  yesterday.setDate(yesterday.getDate() - 1)
  
  if (date.toDateString() === today.toDateString()) {
    return 'Today'
  } else if (date.toDateString() === yesterday.toDateString()) {
    return 'Yesterday'
  } else {
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
  }
}

const shouldShowDateDivider = (message, index) => {
  if (index === 0) return true
  
  const prevMessage = props.messages[index - 1]
  const currentDate = new Date(message.created_at).toDateString()
  const prevDate = new Date(prevMessage.created_at).toDateString()
  
  return currentDate !== prevDate
}

const getReadByUsers = (message) => {
  if (!message.reads || !props.conversation.participants) return []
  
  return message.reads
    .filter(read => read.id !== authUser.value?.id)
    .map(read => {
      const user = props.conversation.participants.find(p => p.id === read.id)
      return user?.name
    })
    .filter(Boolean)
}

const isMessageRead = (message) => {
  if (isMyMessage(message)) {
    return getReadByUsers(message).length > 0
  }
  return false
}

const setupIntersectionObserver = () => {
  observer.value = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const messageId = entry.target.dataset.messageId
          const message = props.messages.find(m => m.id === parseInt(messageId))
          
          if (message && !isMyMessage(message)) {
            // Check if not already read
            const alreadyRead = message.reads?.some(r => r.id === authUser.value?.id)
            if (!alreadyRead) {
              emit('markRead', message.id)
            }
          }
        }
      })
    },
    {
      root: messagesContainer.value,
      threshold: 0.5
    }
  )
}

watch(() => props.messages.length, () => {
  scrollToBottom()
  
  // Observe new messages for read receipts
  nextTick(() => {
    const messageElements = messagesContainer.value?.querySelectorAll('[data-message-id]')
    messageElements?.forEach(el => {
      observer.value?.observe(el)
    })
  })
})

onMounted(() => {
  scrollToBottom()
  setupIntersectionObserver()
  
  // Observe initial messages
  nextTick(() => {
    const messageElements = messagesContainer.value?.querySelectorAll('[data-message-id]')
    messageElements?.forEach(el => {
      observer.value?.observe(el)
    })
  })
})
</script>

<template>
  <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
    <template v-for="(message, index) in messages" :key="message.id">
      <!-- Date Divider -->
      <div v-if="shouldShowDateDivider(message, index)" class="flex items-center justify-center my-4">
        <div class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">
          {{ formatDate(message.created_at) }}
        </div>
      </div>

      <!-- Message -->
      <div 
        :class="['flex', isMyMessage(message) ? 'justify-end' : 'justify-start']"
        :data-message-id="message.id"
      >
        <div :class="['max-w-[75%] flex gap-2', isMyMessage(message) ? 'flex-row-reverse' : 'flex-row']">
          <!-- Avatar (only for other users) -->
          <div v-if="!isMyMessage(message)" class="flex-shrink-0">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-sm font-semibold">
              {{ message.user.name.charAt(0).toUpperCase() }}
            </div>
          </div>

          <!-- Message Content -->
          <div>
            <!-- Sender name (only for other users) -->
            <div v-if="!isMyMessage(message)" class="text-xs text-gray-600 mb-1 px-1">
              {{ message.user.name }}
            </div>

            <!-- Message bubble -->
            <div
              :class="[
                'rounded-lg px-4 py-2 break-words',
                isMyMessage(message) 
                  ? 'bg-blue-600 text-white' 
                  : 'bg-white text-gray-900 border border-gray-200'
              ]"
            >
              <!-- Attachment -->
              <div v-if="message.attachment_path" class="mb-2">
                
                  :href="`/storage/${message.attachment_path}`"
                  target="_blank"
                  :class="[
                    'flex items-center gap-2 p-2 rounded',
                    isMyMessage(message) ? 'bg-blue-700' : 'bg-gray-100'
                  ]"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                  </svg>
                  <span class="text-sm truncate">{{ message.attachment_name }}</span>
               
              </div>

              <!-- Message text -->
              <p v-if="message.body" class="whitespace-pre-wrap">{{ message.body }}</p>
            </div>

            <!-- Message metadata -->
            <div :class="['text-xs mt-1 px-1 flex items-center gap-2', isMyMessage(message) ? 'justify-end' : 'justify-start']">
              <span class="text-gray-500">{{ formatTime(message.created_at) }}</span>
              
              <!-- Read receipts (only for my messages) -->
              <div
                v-if="isMyMessage(message)"
                class="flex items-center"
                :title="isMessageRead(message) ? `Read by ${getReadByUsers(message).join(', ')}` : 'Sent'"
              >
                <!-- Double check for read -->
                <svg 
                  v-if="isMessageRead(message)"
                  class="w-4 h-4 text-blue-400" 
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13l4 4L23 7" transform="translate(-2, 0)" />
                </svg>
                <!-- Single check for sent but not read -->
                <svg 
                  v-else
                  class="w-4 h-4 text-gray-400" 
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- Typing Indicator -->
    <div v-if="typingUsers.length > 0" class="flex justify-start">
      <div class="max-w-[75%] flex gap-2">
        <div class="flex-shrink-0">
          <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-sm font-semibold">
            {{ typingUsers[0].name.charAt(0).toUpperCase() }}
          </div>
        </div>
        
        <div>
          <div class="text-xs text-gray-600 mb-1 px-1">
            {{ typingUsers[0].name }}
          </div>
          <div class="bg-white border border-gray-200 rounded-lg px-4 py-3">
            <div class="flex gap-1">
              <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
              <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
              <div class="w-2 h-2 bg-gray-400 rounded-full" style="animation-delay: 300ms"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-0.5rem); }
}

.animate-bounce {
  animation: bounce 1s infinite;
}
</style>