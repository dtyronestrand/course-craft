<script setup>
import { ref } from 'vue';

const emit = defineEmits(['send', 'typing']);

const message = ref('');
const attachment = ref(null);
const fileInput = ref(null);
const isTyping = ref(false);
const typingTimeout = ref(null);

const handleInput = () => {
    if (!isTyping.value) {
        isTyping.value = true;
        emit('typing');
    }

    clearTimeout(typingTimeout.value);
    typingTimeout.value = setTimeout(() => {
        isTyping.value = false;
    }, 1000);
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        attachment.value = file;
    }
};

const removeAttachment = () => {
    attachment.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const sendMessage = () => {
    if (!message.value.trim() && !attachment.value) return;

    emit('send', message.value.trim(), attachment.value);

    message.value = '';
    attachment.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
    isTyping.value = false;
    clearTimeout(typingTimeout.value);
};

const handleKeydown = (event) => {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
};
</script>

<template>
    <div class="border-t bg-white">
        <!-- Attachment Preview -->
        <div v-if="attachment" class="border-b bg-gray-50 px-4 pt-3 pb-2">
            <div
                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-2"
            >
                <div class="flex min-w-0 flex-1 items-center gap-2">
                    <svg
                        class="h-5 w-5 flex-shrink-0 text-gray-500"
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
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900">
                            {{ attachment.name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ formatFileSize(attachment.size) }}
                        </p>
                    </div>
                </div>
                <button
                    @click="removeAttachment"
                    class="flex-shrink-0 rounded p-1 transition-colors hover:bg-gray-100"
                    type="button"
                >
                    <svg
                        class="h-5 w-5 text-gray-500"
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
        </div>

        <!-- Input Area -->
        <div class="flex items-end gap-2 p-4">
            <!-- Attachment Button -->
            <button
                @click="fileInput?.click()"
                class="flex-shrink-0 rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100"
                type="button"
                title="Attach file"
            >
                <svg
                    class="h-6 w-6"
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
                    class="max-h-32 w-full resize-none rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                ></textarea>
            </div>

            <!-- Send Button -->
            <button
                @click="sendMessage"
                :disabled="!message.trim() && !attachment"
                :class="[
                    'flex-shrink-0 rounded-lg p-2 transition-colors',
                    message.trim() || attachment
                        ? 'bg-blue-600 text-white hover:bg-blue-700'
                        : 'cursor-not-allowed bg-gray-200 text-gray-400',
                ]"
                type="button"
            >
                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>
