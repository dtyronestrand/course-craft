<script setup>
import axios from 'axios';
import { computed, ref } from 'vue';

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close', 'created']);

const conversationType = ref('direct');
const selectedUsers = ref([]);
const searchQuery = ref('');
const searchResults = ref([]);
const isSearching = ref(false);
const conversationName = ref('');
const selectedCourse = ref(null);
const selectedProject = ref(null);
const courses = ref([]);

const searchUsers = async () => {
    if (searchQuery.value.length < 2) {
        searchResults.value = [];
        return;
    }

    isSearching.value = true;
    try {
        const response = await axios.get('/api/users/search', {
            params: { q: searchQuery.value },
        });
        searchResults.value = response.data;
    } catch (error) {
        console.error('Failed to search users:', error);
    } finally {
        isSearching.value = false;
    }
};

const fetchCourses = async () => {
    try {
        const response = await axios.get('/api/courses');
        courses.value = response.data;
    } catch (error) {
        console.error('Failed to fetch courses:', error);
    }
};

const toggleUser = (user) => {
    const index = selectedUsers.value.findIndex((u) => u.id === user.id);
    if (index > -1) {
        selectedUsers.value.splice(index, 1);
    } else {
        if (
            conversationType.value === 'direct' &&
            selectedUsers.value.length > 0
        ) {
            selectedUsers.value = [user];
        } else {
            selectedUsers.value.push(user);
        }
    }
};

const isUserSelected = (user) => {
    return selectedUsers.value.some((u) => u.id === user.id);
};

const canCreate = computed(() => {
    if (conversationType.value === 'direct') {
        return selectedUsers.value.length === 1;
    } else if (conversationType.value === 'group') {
        return selectedUsers.value.length > 0 && conversationName.value.trim();
    } else if (conversationType.value === 'project') {
        return selectedProject.value !== null;
    }
    return false;
});

const createConversation = async () => {
    if (!canCreate.value) return;

    const data = {
        type: conversationType.value,
        user_ids: selectedUsers.value.map((u) => u.id),
    };

    if (conversationType.value === 'group') {
        data.name = conversationName.value;
    } else if (conversationType.value === 'project') {
        data.project_id = selectedProject.value;
    }

    try {
        emit('created', data);
        resetForm();
    } catch (error) {
        console.error('Failed to create conversation:', error);
    }
};

const resetForm = () => {
    conversationType.value = 'direct';
    selectedUsers.value = [];
    searchQuery.value = '';
    searchResults.value = [];
    conversationName.value = '';
    selectedProject.value = null;
    emit('close');
};

const handleTypeChange = () => {
    if (conversationType.value === 'course') {
        fetchCourses();
    }
};

defineExpose({ resetForm });
</script>

<template>
    <div
        v-if="open"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @click.self="emit('close')"
    >
        <div class="bg-opacity-50 fixed inset-0 bg-black"></div>

        <div
            class="relative flex max-h-[80vh] w-full max-w-md flex-col overflow-hidden rounded-lg bg-white shadow-xl"
        >
            <!-- Header -->
            <div class="flex items-center justify-between border-b p-4">
                <h3 class="text-lg font-semibold">New Conversation</h3>
                <button
                    @click="emit('close')"
                    class="rounded p-1 transition-colors hover:bg-gray-100"
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
            <div class="flex-1 space-y-4 overflow-y-auto p-4">
                <!-- Type Selection -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700"
                        >Type</label
                    >
                    <div class="flex gap-2">
                        <button
                            @click="
                                conversationType = 'direct';
                                handleTypeChange();
                            "
                            :class="[
                                'flex-1 rounded-lg border px-4 py-2 transition-colors',
                                conversationType === 'direct'
                                    ? 'border-blue-600 bg-blue-50 text-blue-600'
                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
                            ]"
                        >
                            Direct
                        </button>
                        <button
                            @click="
                                conversationType = 'group';
                                handleTypeChange();
                            "
                            :class="[
                                'flex-1 rounded-lg border px-4 py-2 transition-colors',
                                conversationType === 'group'
                                    ? 'border-blue-600 bg-blue-50 text-blue-600'
                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
                            ]"
                        >
                            Group
                        </button>
                        <button
                            @click="
                                conversationType = 'course';
                                handleTypeChange();
                            "
                            :class="[
                                'flex-1 rounded-lg border px-4 py-2 transition-colors',
                                conversationType === 'course'
                                    ? 'border-blue-600 bg-blue-50 text-blue-600'
                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
                            ]"
                        >
                            Course
                        </button>
                    </div>
                </div>

                <!-- Group Name -->
                <div v-if="conversationType === 'group'">
                    <label class="mb-2 block text-sm font-medium text-gray-700"
                        >Group Name</label
                    >
                    <input
                        v-model="conversationName"
                        type="text"
                        placeholder="Enter group name"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    />
                </div>

                <!-- Project Selection -->
                <div v-if="conversationType === 'course'">
                    <label class="mb-2 block text-sm font-medium text-gray-700"
                        >Select Course</label
                    >
                    <select
                        v-model="selectedCourse"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >
                        <option :value="null">Choose a course</option>
                        <option
                            v-for="course in courses"
                            :key="course.id"
                            :value="course.id"
                        >
                            {{ course.prefix }} {{ course.number }}
                        </option>
                    </select>
                </div>

                <!-- User Search (for direct and group) -->
                <div v-if="conversationType !== 'course'">
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        {{
                            conversationType === 'direct'
                                ? 'Select User'
                                : 'Add Members'
                        }}
                    </label>

                    <!-- Search Input -->
                    <div class="relative">
                        <input
                            v-model="searchQuery"
                            @input="searchUsers"
                            type="text"
                            placeholder="Search users..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        />
                        <div
                            v-if="isSearching"
                            class="absolute top-2.5 right-3"
                        >
                            <svg
                                class="h-5 w-5 animate-spin text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Selected Users -->
                    <div
                        v-if="selectedUsers.length > 0"
                        class="mt-2 flex flex-wrap gap-2"
                    >
                        <div
                            v-for="user in selectedUsers"
                            :key="user.id"
                            class="flex items-center gap-1 rounded-full bg-blue-100 px-2 py-1 text-sm text-blue-800"
                        >
                            <span
                                >{{ user.first_name }}
                                {{ user.last_name }}</span
                            >
                            <button
                                @click="toggleUser(user)"
                                class="rounded-full p-0.5 hover:bg-blue-200"
                            >
                                <svg
                                    class="h-4 w-4"
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

                    <!-- Search Results -->
                    <div
                        v-if="searchResults.length > 0"
                        class="mt-2 max-h-48 divide-y overflow-y-auto rounded-lg border border-gray-200"
                    >
                        <button
                            v-for="user in searchResults"
                            :key="user.id"
                            @click="toggleUser(user)"
                            :class="[
                                'flex w-full items-center justify-between p-3 text-left transition-colors hover:bg-gray-50',
                                isUserSelected(user) && 'bg-blue-50',
                            ]"
                        >
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-sm font-semibold text-white"
                                >
                                    {{
                                        (user.first_name || '')
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ user.first_name }}
                                        {{ user.last_name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>
                            <svg
                                v-if="isUserSelected(user)"
                                class="h-5 w-5 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="flex items-center justify-end gap-2 border-t bg-gray-50 p-4"
            >
                <button
                    @click="emit('close')"
                    class="rounded-lg px-4 py-2 text-gray-700 transition-colors hover:bg-gray-200"
                >
                    Cancel
                </button>
                <button
                    @click="createConversation"
                    :disabled="!canCreate"
                    :class="[
                        'rounded-lg px-4 py-2 transition-colors',
                        canCreate
                            ? 'bg-blue-600 text-white hover:bg-blue-700'
                            : 'cursor-not-allowed bg-gray-300 text-gray-500',
                    ]"
                >
                    Create
                </button>
            </div>
        </div>
    </div>
</template>
