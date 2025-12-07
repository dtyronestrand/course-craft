<template>
    <div
        v-for="user in usersWorkloads"
        :key="user.last_name"
        class="flex items-center justify-between gap-4 rounded-lg shadow-sm shadow-base-200/20"
        v-bind="$attrs"
    >
        <div class="flex justify-evenly">
            <div class="placeholder avatar">
                <div
                    class="frosted-backdrop flex h-8 w-8 items-center rounded-full border border-primary bg-primary/10 text-primary shadow-sm shadow-primary/20"
                >
                    <p class="mx-auto text-sm">
                        {{ getInitials(user.first_name, user.last_name) }}
                    </p>
                </div>
            </div>
            <progress
                class="progress mx-4 w-50 place-self-center progress-primary"
                :value="user.workload"
                max="100"
            ></progress>
            <p class="place-self-center text-xs">{{ user.workload }}%</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { getInitials } from '@/composables/useInitials';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';

const users = ref([]);
const capacity = ref(0);
const usersWorkloads = computed(() => {
    return users.value.map((user: any) => ({
        first_name: user.first_name,
        last_name: user.last_name,
        workload: Math.round((user.courses_count / capacity.value) * 100),
    }));
});

onMounted(async () => {
    try {
        await axios.get('/sanctum/csrf-cookie');
        const [usersResponse, capacityResponse] = await Promise.all([
            axios.get('/api/users'),
            axios.get('/api/capacity'),
        ]);
        users.value = usersResponse.data;
        capacity.value = capacityResponse.data.capacity;
    } catch (error) {
        console.error('Error fetching user workloads:', error);
    }
});
</script>

<style scoped></style>
