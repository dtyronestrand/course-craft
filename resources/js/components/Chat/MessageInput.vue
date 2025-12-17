<script setup>
import { ref, watch } from 'vue'

const emit = defineEmits(['send', 'typing'])

const message = ref('')
const attachment = ref(null)
const fileInput = ref(null)
const isTyping = ref(false)
const typingTimeout = ref(null)

const handleInput = () => {
  if (!isTyping.value) {
    isTyping.value = true
    emit('typing')
  }
  
  clearTimeout(typingTimeout.value)
  typingTimeout.value = setTimeout(() => {
    isTyping.value = false
  }, 1000)
}

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    attachment.value = file
  }
}

const removeAttachment = () => {
  attachment.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const sendMessage = () => {
  if (!message.value.trim() && !attachment.value) return
  
  emit('send', message.value.trim(), attachment.value)
  
  message.value = ''
  attachment.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
  isTyping.value = false
  clearTimeout(typingTimeout.value)
}

const handleKeydown = (event) => {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault()
    sendMessage()
  }
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}
</script>

<template>
  <div class="border-t bg-white">
    <!-- Attachment Preview -->
    <div v-if="attachment" class="px-4 pt-3 pb-2 border-b bg-gray-50">
      <div class="flex items-center justify-between bg-white border border-gray-200 rounded-lg p-2">
        <div class="flex items-center gap-2 flex-1 min-w-0">
          <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
          </svg>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ attachment.name }}</p>
            <p class="text-xs text-gray-500">{{ formatFileSize(attachment.size) }}</p>
          </div>
        </div>
        <button
          @click="removeAttachment"
          class="p-1 hover:bg-gray-100 rounded transition-colors flex-shrink-0"
          type="button"
        >
          <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Input Area -->
    <div class="p-4 flex items-end gap-2">
      <!-- Attachment Button -->
      <button
        @click="fileInput?.click()"
        class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors flex-shrink-0"
        type="button"
        title="Attach file"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
        </svg>
      </button>

      <input
        ref="fileInput"
        type="file"
        class="hidden"
        @change="handleFileSelect"
      />

      <!-- Text Input -->
      <div class="flex-1">
        <textarea
          v-model="message"
          @input="handleInput"
          @keydown="handleKeydown"
          placeholder="Type a message..."
          rows="1"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none max-h-32"
        ></textarea>
      </div>

      <!-- Send Button -->
      <button
        @click="sendMessage"
        :disabled="!message.trim() && !attachment"
        :class="[
          'p-2 rounded-lg transition-colors flex-shrink-0',
          message.trim() || attachment
            ? 'bg-blue-600 text-white hover:bg-blue-700'
            : 'bg-gray-200 text-gray-400 cursor-not-allowed'
        ]"
        type="button"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
        </svg>
      </button>
    </div>
  </div>
</template>