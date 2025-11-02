<template>
 <New>
    <BasicInfo :course="course"/>
    <StoryboardComponent :numberOfModules="numberOfModules" :course="course"/>
 </New>
</template>

<script setup lang="ts">
import BasicInfo from '@/components/Course/BasicInfo.vue';
import New from '@/layouts/New.vue';
import {Course} from "@/types";
import StoryboardComponent from '@/components/Course/StoryboardComponent.vue';

defineProps<{
    course: Course;
    numberOfModules: number;
}>();
</script>

<style scoped>

</style>