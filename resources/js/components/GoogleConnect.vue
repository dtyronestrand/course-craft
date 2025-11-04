<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold">Google Drive Integration</h3>
        <p class="text-sm text-gray-600 mt-1">
          {{props.isConnected 
            ? 'Your Google account is connected' 
            : 'Connect your Google account to create documents' 
          }}
        </p>
      </div>
      
      <div>
        <button
          v-if="!isConnected"
          @click="connectGoogle"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-2"
        >
          <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          Connect Google
        </button>
        
        <button
          v-else
          @click="disconnectGoogle"
          :disabled="processing"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50"
        >
          {{ processing ? 'Disconnecting...' : 'Disconnect' }}
        </button>
      </div>
    </div>
    
    <div v-if="isConnected" class="mt-4 text-sm text-green-600 flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
      </svg>
      Connected and ready to create documents
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  isConnected: {
    type: Boolean,
    default: false
  }
});

const processing = ref(false);

const connectGoogle = () => {
  router.get('/google/redirect');
};

const disconnectGoogle = () => {
  if (!confirm('Are you sure you want to disconnect your Google account?')) {
    return;
  }
  
  processing.value = true;
  
  router.post('/google/disconnect', {}, {
    onFinish: () => {
      processing.value = false;
    }
  });
};
</script>