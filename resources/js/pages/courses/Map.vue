<template>
    <New>
        <BasicInfo :course="course" />
        <MapComponent :numberOfModules="numberOfModules" :course="course" />
    </New>
</template>

<script setup lang="ts">
import BasicInfo from '@/components/Course/BasicInfo.vue';
import MapComponent from '@/components/Course/Map.vue';
import New from '@/layouts/New.vue';
import { Course } from '@/types';

defineProps<{
    course: Course;
    numberOfModules: number;
}>();
</script>

<style scoped></style>
