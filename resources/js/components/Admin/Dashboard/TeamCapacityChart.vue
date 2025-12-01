<template>
    <div v-for="user in usersWorkloads" :key="user.last_name" class="flex items-center justify-between mb-4 p-4 rounded-lg shadow-sm shadow-base-200/20">
    <div class="flex justify-between">
       <div class="avatar placeholder">
        <div class="flex items-center bg-primary/10 border border-primary shadow-sm shadow-primary/20 frosted-backdrop text-primary rounded-full w-10 h-10">
        <p class="text-sm mx-auto">{{ getInitials(user.first_name, user.last_name) }}</p>

        </div>
        </div>
                <progress class="place-self-center mx-4 progress progress-primary w-50" :value="user.workload" max="100"></progress>
                <p class="text-xs place-self-center"> {{ user.workload }}%</p>
    </div>
        
    </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import {ref, onMounted, computed} from 'vue';
import { getInitials } from '@/composables/useInitials';


const users = ref([]);
const capacity = ref(0);
const usersWorkloads = computed(() => {
    return users.value.map((user: any) => ({
        first_name: user.first_name,
        last_name: user.last_name,
        workload: Math.round(user.courses_count/capacity.value * 100),
    }));
});


onMounted(async () => {
    try {
        await axios.get('/sanctum/csrf-cookie');
        const [usersResponse, capacityResponse] = await Promise.all([
            axios.get('/api/users'),
            axios.get('/api/capacity')
        ]);
        users.value = usersResponse.data;
        capacity.value = capacityResponse.data.capacity;
    } catch (error) {
        console.error('Error fetching user workloads:', error);
    }
});
</script>

<style scoped>

</style>