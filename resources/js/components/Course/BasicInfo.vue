<template>
    <div class="bg-base-100 p-12 text-base-content">
        <div class="rounded-t-lg border bg-primary p-8">
            <h1 class="mb-4 text-7xl text-base-content">
                {{ props.course.prefix }} {{ props.course.number }}
            </h1>
            <h2 class="mb-4 text-5xl text-base-content">
                {{ props.course.title }}
            </h2>
            <div class="border bg-base-200 p-8">
                <div v-for="(user, index) in props.course.users" :key="index">
                    <p>
                        {{ user.pivot?.role }}: {{ user.first_name }}
                        {{ user.last_name }}
                    </p>
                </div>
                <h3 class="my-4 text-3xl">Learning Objectives:</h3>
                <div
                    v-for="(objective, index) in props.course.objectives"
                    :key="index"
                >
                    <p class="text-lg">
                        {{ objective.number }}. {{ objective.objective }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { Course } from '@/types';

interface Props {
    course: Course;
}
const props = defineProps<Props>();
</script>
