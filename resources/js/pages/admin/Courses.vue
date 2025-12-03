<template>
<AdminLayout>
<div class="flex flex-col w-full p-8 align-center">
            <h1 class="text-7xl  block text-center my-8">All Courses</h1>
            <div class="flex flex-row justify-between">
            <div class="flex flex-row gap-2 items-center"> 
            <p>View: </p><button class="btn btn-sm hover:bg-neutral" @click="view = 'list'"><List/></button><button class="hover:bg-neutral btn btn-sm" @click="view = 'grid'"><Grid2x2/></button>
            </div>
            <button class="btn bg-info/20 border border-info text-info shadow-md shadow-info/20 frosted-backdrop mb-6 self-end" @click="isCreateCourseModalOpen = true">Create New Course</button>
            </div>
            <div v-if="view === 'list'">
  <CoursesTable  :courses="page.props.courses as any[]"/>
            </div>
  <div v-else class="flex flex-row gap-4">
  
  
  </div>


</div>
</AdminLayout>
 <CreateCourse v-if="isCreateCourseModalOpen" @create-course="saveCourse" @close="isCreateCourseModalOpen = false" />


</template>

<script setup lang="ts">

import { usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import CoursesTable from '@/components/Admin/Courses/CoursesTable.vue';
import {List, Grid2x2} from 'lucide-vue-next';
import { ref } from 'vue';
import CreateCourse from '@/components/Course/CreateCourse.vue';

const page = usePage();

const isCreateCourseModalOpen = ref(false);
const view = ref('list');

const saveCourse = (courseData: any) => {
    // Handle course creation logic here
    router.post('/courses', courseData, );
    isCreateCourseModalOpen.value = false;
    router.get('/dashboard');
};




   
</script>

<style scoped>

</style>