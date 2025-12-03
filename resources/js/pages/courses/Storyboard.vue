<template>
    <New>
        <BasicInfo :course="course" />
        <StoryboardComponent
            :numberOfModules="numberOfModules"
            :course="course"
        />
    </New>
</template>

<script setup lang="ts">
import BasicInfo from '@/components/Course/BasicInfo.vue';
import StoryboardComponent from '@/components/Course/StoryboardComponent.vue';
import New from '@/layouts/New.vue';
import { Course } from '@/types';

defineProps<{
    course: Course;
    numberOfModules: number;
}>();
</script>

<style scoped></style>
