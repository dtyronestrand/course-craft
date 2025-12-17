<template>
    <div class="-mr-2 space-y-6 overflow-y-auto pr-2">
        <div
            v-for="activity in activities"
            :key="activity.id"
            class="flex gap-4 rounded-lg transition-colors hover:bg-base-200"
        >
            <div class="placeholder avatar">
                <div
                    class="frosted-backdrop flex h-10 w-10 items-center rounded-full border border-primary bg-primary/10 text-primary shadow-sm shadow-primary/20"
                >
                    <p class="mx-auto text-sm">{{ activity.user.initials }}</p>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm">{{ activity.description }}</p>
                <span class="text-xs text-base-content/60">{{
                    activity.timestamp
                }}</span>
            </div>
        </div>
        <div
            v-if="activities.length === 0"
            class="text-center text-base-content/60"
        >
            No recent activity
        </div>
    </div>
</template>

<script setup lang="ts">
import { echo } from '@laravel/echo-vue';
import { onMounted, onUnmounted, ref } from 'vue';

interface Activity {
    id: number;
    user: { name: string; initials: string };
    action: string;
    description: string;
    timestamp: string;
    created_at: string;
}

const props = withDefaults(
    defineProps<{
        initialActivities?: Activity[];
    }>(),
    {
        initialActivities: () => [],
    },
);

const activities = ref<Activity[]>(props.initialActivities || []);

let channel: any;

onMounted(() => {
    channel = echo()
        .channel('activities')
        .listen(
            'ActivityCreated',
            (event: {
                id: number;
                user: any;
                action: string;
                description: string;
                timestamp: string;
                created_at: string;
            }) => {
                activities.value.unshift(event);
                if (activities.value.length > 10) {
                    activities.value.pop();
                }
            },
        );
});

onUnmounted(() => {
    if (channel) {
        echo().leave('activities');
    }
});
</script>

<style scoped></style>
