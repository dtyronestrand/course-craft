<template>
<AdminLayout>
<div class="flex flex-col w-full p-8 align-center">
            <h1 class="text-7xl  block text-center my-8">All Courses</h1>
            <button class="btn btn-info mb-6 self-end" @click="isCreateCourseModalOpen = true">Create New Course</button>
  <CoursesTable  :courses="page.props.courses as any[]"/>


</div>
</AdminLayout>
 <CreateCourse v-if="isCreateCourseModalOpen" @create-course="saveCourse" @close="isCreateCourseModalOpen = false" />


</template>

<script setup lang="ts">

import { usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import CoursesTable from '@/components/Admin/Courses/CoursesTable.vue';
import { ref } from 'vue';
import CreateCourse from '@/components/Course/CreateCourse.vue';

const page = usePage();

const isCreateCourseModalOpen = ref(false);

const saveCourse = (courseData: any) => {
    // Handle course creation logic here
    router.post('/courses', courseData, );
    isCreateCourseModalOpen.value = false;
    router.get('/dashboard');
};




   
</script>

<style scoped>

</style>