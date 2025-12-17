<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'

const props = defineProps({
  open: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'created'])

const conversationType = ref('direct')
const selectedUsers = ref([])
const searchQuery = ref('')
const searchResults = ref([])
const isSearching = ref(false)
const conversationName = ref('')
const selectedProject = ref(null)
const projects = ref([])

const searchUsers = async () => {
  if (searchQuery.value.length < 2) {
    searchResults.value = []
    return
  }

  isSearching.value = true
  try {
    const response = await axios.get('/api/users/search', {
      params: { q: searchQuery.value }
    })
    searchResults.value = response.data
  } catch (error) {
    console.error('Failed to search users:', error)
  } finally {
    isSearching.value = false
  }
}

const fetchProjects = async () => {
  try {
    const response = await axios.get('/api/projects')
    projects.value = response.data
  } catch (error) {
    console.error('Failed to fetch projects:', error)
  }
}

const toggleUser = (user) => {
  const index = selectedUsers.value.findIndex(u => u.id === user.id)
  if (index > -1) {
    selectedUsers.value.splice(index, 1)
  } else {
    if (conversationType.value === 'direct' && selectedUsers.value.length > 0) {
      selectedUsers.value = [user]
    } else {
      selectedUsers.value.push(user)
    }
  }
}

const isUserSelected = (user) => {
  return selectedUsers.value.some(u => u.id === user.id)
}

const canCreate = computed(() => {
  if (conversationType.value === 'direct') {
    return selectedUsers.value.length === 1
  } else if (conversationType.value === 'group') {
    return selectedUsers.value.length > 0 && conversationName.value.trim()
  } else if (conversationType.value === 'project') {
    return selectedProject.value !== null
  }
  return false
})

const createConversation = async () => {
  if (!canCreate.value) return

  const data = {
    type: conversationType.value,
    user_ids: selectedUsers.value.map(u => u.id),
  }

  if (conversationType.value === 'group') {
    data.name = conversationName.value
  } else if (conversationType.value === 'project') {
    data.project_id = selectedProject.value
  }

  try {
    emit('created', data)
    resetForm()
  } catch (error) {
    console.error('Failed to create conversation:', error)
  }
}

const resetForm = () => {
  conversationType.value = 'direct'
  selectedUsers.value = []
  searchQuery.value = ''
  searchResults.value = []
  conversationName.value = ''
  selectedProject.value = null
  emit('close')
}

const handleTypeChange = () => {
  if (conversationType.value === 'project') {
    fetchProjects()
  }
}

defineExpose({ resetForm })
</script>

<template>
  <div
    v-if="open"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @click.self="emit('close')"
  >
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    
    <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full max-h-[80vh] overflow-hidden flex flex-col">
      <!-- Header -->
      <div class="flex items-center justify-between p-4 border-b">
        <h3 class="text-lg font-semibold">New Conversation</h3>
        <button
          @click="emit('close')"
          class="p-1 hover:bg-gray-100 rounded transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Content -->
      <div class="flex-1 overflow-y-auto p-4 space-y-4">
        <!-- Type Selection -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
          <div class="flex gap-2">
            <button
              @click="conversationType = 'direct'; handleTypeChange()"
              :class="[
                'flex-1 px-4 py-2 rounded-lg border transition-colors',
                conversationType === 'direct'
                  ? 'bg-blue-50 border-blue-600 text-blue-600'
                  : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'
              ]"
            >
              Direct
            </button>
            <button
              @click="conversationType = 'group'; handleTypeChange()"
              :class="[
                'flex-1 px-4 py-2 rounded-lg border transition-colors',
                conversationType === 'group'
                  ? 'bg-blue-50 border-blue-600 text-blue-600'
                  : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'
              ]"
            >
              Group
            </button>
            <button
              @click="conversationType = 'project'; handleTypeChange()"
              :class="[
                'flex-1 px-4 py-2 rounded-lg border transition-colors',
                conversationType === 'project'
                  ? 'bg-blue-50 border-blue-600 text-blue-600'
                  : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'
              ]"
            >
              Project
            </button>
          </div>
        </div>

        <!-- Group Name -->
        <div v-if="conversationType === 'group'">
          <label class="block text-sm font-medium text-gray-700 mb-2">Group Name</label>
          <input
            v-model="conversationName"
            type="text"
            placeholder="Enter group name"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Project Selection -->
        <div v-if="conversationType === 'project'">
          <label class="block text-sm font-medium text-gray-700 mb-2">Select Project</label>
          <select
            v-model="selectedProject"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option :value="null">Choose a project</option>
            <option v-for="project in projects" :key="project.id" :value="project.id">
              {{ project.name }}
            </option>
          </select>
        </div>

        <!-- User Search (for direct and group) -->
        <div v-if="conversationType !== 'project'">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ conversationType === 'direct' ? 'Select User' : 'Add Members' }}
          </label>
          
          <!-- Search Input -->
          <div class="relative">
            <input
              v-model="searchQuery"
              @input="searchUsers"
              type="text"
              placeholder="Search users..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div v-if="isSearching" class="absolute right-3 top-2.5">
              <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
          </div>

          <!-- Selected Users -->
          <div v-if="selectedUsers.length > 0" class="mt-2 flex flex-wrap gap-2">
            <div
              v-for="user in selectedUsers"
              :key="user.id"
              class="flex items-center gap-1 bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm"
            >
              <span>{{ user.name }}</span>
              <button @click="toggleUser(user)" class="hover:bg-blue-200 rounded-full p-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Search Results -->
          <div v-if="searchResults.length > 0" class="mt-2 border border-gray-200 rounded-lg divide-y max-h-48 overflow-y-auto">
            <button
              v-for="user in searchResults"
              :key="user.id"
              @click="toggleUser(user)"
              :class="[
                'w-full p-3 text-left hover:bg-gray-50 transition-colors flex items-center justify-between',
                isUserSelected(user) && 'bg-blue-50'
              ]"
            >
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                  {{ user.name.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <p class="font-medium text-gray-900">{{ user.name }}</p>
                  <p class="text-sm text-gray-500">{{ user.email }}</p>
                </div>
              </div>
              <svg
                v-if="isUserSelected(user)"
                class="w-5 h-5 text-blue-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-2 p-4 border-t bg-gray-50">
        <button
          @click="emit('close')"
          class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors"
        >
          Cancel
        </button>
        <button
          @click="createConversation"
          :disabled="!canCreate"
          :class="[
            'px-4 py-2 rounded-lg transition-colors',
            canCreate
              ? 'bg-blue-600 text-white hover:bg-blue-700'
              : 'bg-gray-300 text-gray-500 cursor-not-allowed'
          ]"
        >
          Create
        </button>
      </div>
    </div>
  </div>
</template>