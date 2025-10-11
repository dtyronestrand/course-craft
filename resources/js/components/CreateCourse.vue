<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-base-200/80 bg-opacity-50 pointer-events-none" @click.self="$emit('close')">
        <div class="glass rounded-lg p-6 w-full max-w-md mx-4 pointer-events-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl text-primary-content font-semibold">Create Course</h2>
                <button @click="$emit('close')" class="text-primary-content hover:text-error">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form @submit.prevent="$emit('create-course', courseData)">
                <div class="mb-4">
                    <label for="prefix" class="block text-sm font-medium text-primary-content">Course Prefix</label>
                    <input v-model="courseData.prefix" type="text" id="prefix" class="mt-1 block w-full border-b border-base-200 shadow-sm p-2" required />
                </div>
                <div class="mb-4">
                    <label for="number" class="block text-sm font-medium text-primary-content">Course Number</label>
                    <input v-model="courseData.number" type="text" id="number" class="mt-1 block w-full border-b border-base-200 rounded- shadow-sm p-2" required />
                </div>
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-primary-content">Course Title</label>
                    <input v-model="courseData.title" type="text" id="title" class="mt-1 block w-full border-b border-base-200 shadow-sm p-2" required />
                </div>
                <div class="mb-4">
                    <label for="objectives" class="block text-sm font-medium text-primary-content">Course Objectives</label>
                <div v-for="(objective, index) in courseData.objectives" :key="index" class="mb-2 flex items-center gap-2">
                    <p class="mb-0">{{objective.number}}.</p>
                    <input v-model="objective.text" type="text" :id="'objective-' + index" class="mt-1 block w-full border border-base-200 rounded-md shadow-sm p-2" />
                    <button type="button" @click="courseData.objectives.splice(index, 1)" class="text-error hover:text-error/70">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <button type="button" @click="createObjective" class="bg-info text-info-content px-4 py-2 rounded-md hover:bg-success/70">Add Objective</button>
                </div>

                <div class="mb-4">
                    <label for="users" class="block text-sm font-medium text-primary-content"> Users</label>
                    <div v-for="user in courseData.users" :key="user.id" class="text-primary-content">
                        {{user.first_name}} {{user.last_name }}
                        <select v-model="user.role" class="ml-2 border border-base-200 rounded-md shadow-sm p-1">
                            <option value="designer">Designer</option>
                            <option value="sme">Subject Matter Expert</option>
                            <option value="manager">Manager</option>
                            <option value="builder">Builder</option>
                        </select>
                    </div>
                    <button type="button" @click ="addUser" class="bg-info text-info-content px-4 py-2 rounded-md hover:bg-success/70 mt-2">Add User</button>
                    <select v-if="addingUser" @change="updateUsers" v-model="selectedUserId" id="users" class="mt-1 block w-full border border-base-200 rounded-md shadow-sm p-2" >
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            {{ user.first_name }} {{ user.last_name }}
                        </option>
                    </select>

                </div>
                <div class="flex gap-2">
                    <button @click="handleCreateCourse" class="bg-success text-success-content px-4 py-2 rounded-md hover:bg-success/70 flex-1">Create Course</button>
                    <button type="button" @click="$emit('close')" class="bg-error text-error-content px-4 py-2 rounded-md hover:bg-error/70">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';

const emit = defineEmits(['create-course', 'close']);

interface User {
    id: number;
    first_name: string;
    last_name: string;
    role?: string;
}

interface Objective {
    number: string;
    text: string;
}

interface CourseData {
    prefix: string;
    number: string;
    title: string;
    objectives: Objective[];
    users: User[];
}
const handleCreateCourse = () => {
    if (courseData.value.prefix && courseData.value.number && courseData.value.title) {
        // Emit the create-course event with the course data
        // Ensure objectives without text are filtered out
        const filteredObjectives = courseData.value.objectives.filter(obj => obj.text.trim() !== '');
        const courseToCreate = {
            ...courseData.value,
            objectives: filteredObjectives
        };
        // Emit the event
        emit('create-course', courseToCreate);
        // Reset the form
        courseData.value = {
            prefix: '',
            number: '',
            title: '',
            objectives: [],
            users: []
        };
    } else {
        alert('Please fill in all required fields.');
    }
};
const toRoman = (num: number): string => {
  if (typeof num !== 'number' || num <= 0 || !Number.isInteger(num)) {
    return '';
  }
  
  const lookup: Record<string, number> = {
    M: 1000,
    CM: 900,
    D: 500,
    CD: 400,
    C: 100,
    XC: 90,
    L: 50,
    XL: 40,
    X: 10,
    IX: 9,
    V: 5,
    IV: 4,
    I: 1,
  };
  
  let roman = '';
  for (const key in lookup) {
    while (num >= lookup[key]) {
      roman += key;
      num -= lookup[key];
    }
  }
  return roman;
};

const users = ref<User[]>([]);
const courseData = ref<CourseData>({
    prefix: '',
    number: '',
    title: '',
    objectives: [],
    users: []
});
const addingUser = ref(false);
const selectedUserId = ref<number | null>(null);

const addUser = () => {
    addingUser.value = true;
};

const updateUsers = () => {
    if (selectedUserId.value) {
        const selectedUser = users.value.find(user => user.id === selectedUserId.value);
        if (selectedUser && !courseData.value.users.some(user => user.id === selectedUserId.value)) {
            courseData.value.users.push({ ...selectedUser, role: 'designer' });
        }
        addingUser.value = false;
        selectedUserId.value = null;
    }
};
const createObjective = () => {
    courseData.value.objectives.push({ 
        number: toRoman(courseData.value.objectives.length + 1),
        text: '' 
    });
};

onMounted(async () => {
    try {
        const response = await fetch('/courses/create', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        users.value = data.users;
    } catch (error) {
        console.error('Failed to fetch users:', error);
    }
});
</script>

<style scoped>
.pointer-events-none {
    pointer-events: none;
}
.pointer-events-auto {
    pointer-events: auto;
}
</style>