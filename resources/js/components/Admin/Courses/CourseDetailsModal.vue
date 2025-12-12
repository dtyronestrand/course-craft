<template>
    <div
        v-if="props.isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-base-300/80" @click.self="emit('modal-close')"
    >   
        <div
            class="bg-base-100 pointer-events-auto w-full max-w-lg rounded-lg border border-primary p-6 shadow-lg shadow-primary"
            ref="target"
        >
                
                    <h1 class="text-3xl text-center font-bold">
                     Edit   
          </h1>
  
                <form class="flex flex-col flex-wrap gap-4"  @submit.prevent="updateCourse">
<div class="flex flex-row justify-between items-center">
                <label for="prefix">Course Prefix:</label>
                    <input class="my-4 px-4 border border-primary bg-base-200" type="text" name="prefix" v-model="localCourse.prefix"/>
</div>
<div class="items-center flex flex-row justify-between">
                    <label for="number">Course Number:</label>
                    <input class="my-4 px-4 border border-primary bg-base-200" type="text" name="number" v-model="localCourse.number"/>
</div>
           
             <label for="title">Course Title:</label>
                    <input class="my-4 px-4 border border-primary bg-base-200" type="text" name="title" v-model="localCourse.title"/>
              
   <div class="mb-4">
                    <label
                        for="cycle"
                        class="block text-sm font-medium text-base-content"
                        >Development Cycle</label
                    >
                    <select
                        v-model="localCourse.development_cycle_id"
                        id="cycle"
                        class="my-4 px-4 border border-primary bg-base-200"
                    >
<option :value="null">Select a cycle</option>
                        <option
                            v-for="cycle in developmentCycles"
                            :key="cycle.id"
                            :value="cycle.id"
                        >
                            {{ cycle.name }}
                        </option>
                    </select>
                </div>
             
                    <ul class="mt-4 flex flex-row flex-wrap items-center justify-center gap-8">
                        <li
     
                            class="flex flex-col items-center justify-center gap-2"
                            v-for="(user, index) in course.users"
                            :key="user.id"
                        >
                            <div
                                class="frosted-backdrop flex h-8 w-8 items-center justify-center rounded-full border border-primary bg-primary/10 p-4 text-primary shadow shadow-primary"
                            >
                                {{
                                    getInitials(user.first_name, user.last_name)
                                }}
                            </div>
                           <select
                            v-model="user.pivot!.role"
                            class="my-4 px-4 border border-primary bg-base-200"
                        >
                            <option value="Designer">Designer</option>
                            <option value="SME">Subject Matter Expert</option>
                            <option value="Manager">Manager</option>
                            <option value="Builder">Builder</option>
                        </select>
                        <button type="button" @click="localCourse.users.splice(index, 1)" class="font-bold text-error ">X</button>
                        </li>
        <li v-if="!showAddUser && localCourse.users.length < 4">
            <button type="button" class="btn btn-primary text-primary-content hover:bg-primary/30 active:bg-primary/50" @click="handleShowAddUser">Add User</button>
        </li>
        <li v-if="showAddUser" class="flex flex-col gap-2">
            <select v-model="selectedUserId" class="my-4 px-4 border border-primary bg-base-200">
                <option :value="null">Select User</option>
                <option v-for="user in availableUsers" :key="user.id" :value="user.id">
                    {{ user.first_name }} {{ user.last_name }}
                </option>
            </select>
            <select v-model="selectedRole" class="my-4 px-4 border border-primary bg-base-200">
                <option value="Designer">Designer</option>
                <option value="SME">Subject Matter Expert</option>
                <option value="Manager">Manager</option>
                <option value="Builder">Builder</option>
            </select>
            <div class="flex gap-2">
                <button type="button" @click="addUser" class="btn btn-success text-success-content">Add</button>
                <button type="button" @click="cancelAddUser" class="btn btn-error text-error-content ">Cancel</button>
            </div>
        </li>
                    </ul>
                    <div class="flex flex-row gap-4 mt-4">
                    <button type="submit" class="btn btn-success text-success-content hover:bg-success/30 active:bg-success/50">
                        Save Changes
                    </button>
                    <button type="button" class="btn btn-error text-success-error hover:bg-error/30 active:bg-error/50" @click="emit('modal-close')">
                        Cancel
                    </button>
                    </div>
           </form>
                </div>
            </div>
             
         
</template>
<script setup lang="ts">
import { useInitials } from '@/composables/useInitials';
import { onClickOutside } from '@vueuse/core';
import { ref, onMounted } from 'vue';
const { getInitials } = useInitials();
import { router } from '@inertiajs/vue3';
import type {Course} from '@/types'
import axios from 'axios';
const props = defineProps<{
    isOpen: boolean;
    course: any;
   
}>();
const localCourse = ref<Course>(props.course);
const emit = defineEmits(['modal-close']);

const target = ref(null);
const showAddUser = ref(false);
const selectedUserId = ref<number | null>(null);
const selectedRole = ref('Designer');
const availableUsers = ref<any[]>([]);
const developmentCycles = ref<any[]>([]);

const addUser = () => {
    if (!selectedUserId.value) return;
    
    const user = availableUsers.value.find(u => u.id === selectedUserId.value);
    if (user) {
        localCourse.value.users.push({
            ...user,
            pivot: { role: selectedRole.value }
        });
        cancelAddUser();
    }
};

const cancelAddUser = () => {
    showAddUser.value = false;
    selectedUserId.value = null;
    selectedRole.value = 'Designer';
};

const loadAvailableUsers = async () => {
    const response = await axios.get('/api/allUsers');
    const existingUserIds = new Set(localCourse.value.users.map(u => u.id));
    availableUsers.value = response.data.filter((user: any) => !existingUserIds.has(user.id));
};

const loadDevelopmentCycles = async () => {
    const response = await axios.get('/api/development-cycles');
    developmentCycles.value = response.data;
};

onMounted(() => {
    loadDevelopmentCycles();
});
const handleShowAddUser = async () => {
    await loadAvailableUsers();
    showAddUser.value = true;
};
onClickOutside(target, () => {
    emit('modal-close');
});

const updateCourse = ()=>{
    router.post(`/courses/${localCourse.value.id}/update`, localCourse.value);
    emit('modal-close');
}



</script>

<style scoped>
    @reference '../../../../css/app.css';
label{
    @apply text-xl font-bold;
}

</style>
