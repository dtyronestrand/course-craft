<template>
    <div class="space-y-4">
        <div v-for="activity in activities" :key="activity.id" class="flex gap-3 items-start p-3 hover:bg-base-200 rounded-lg transition-colors">
        <div class="avatar placeholder">
        <div class="flex items-center bg-primary text-primary-content rounded-full w-10 h-10">
        <p class="text-sm mx-auto">{{ activity.user.initials }}</p>
        </div>
        </div>
        <div class="flex-1">
        <p class="text-sm">{{ activity.description }}</p>
        <span class="text-xs text-base-content/60">{{ activity.timestamp }}</span>
        </div>
        </div>
        <div v-if="activities.length === 0" class="text-center text-base-content/60">
        No recent activity
        </div>
    </div>
</template>

<script setup lang="ts">
import {ref, onMounted, onUnmounted} from 'vue';
import {echo} from '@laravel/echo-vue';

interface Activity {
    id: number;
    user: {name: string; initials: string;};
    action: string;
    description: string;
    timestamp: string;
    created_at: string;
}

const props = withDefaults(defineProps<{
    initialActivities?: Activity[];
}>(), {
    initialActivities: () => []
});

const activities = ref<Activity[]>(props.initialActivities || []);

let channel: any;

onMounted(()=>{
 
        channel = echo().channel('activities').listen('ActivityCreated', (event: {id: number; user: any; action: string; description: string; timestamp: string; created_at: string;})=>{
            activities.value.unshift(event);
            if(activities.value.length > 10){
                activities.value.pop();
            }
        });


});

onUnmounted(()=>{
    if(channel){
      
            echo().leave('activities');
  
    }
});
</script>

<style scoped>

</style>