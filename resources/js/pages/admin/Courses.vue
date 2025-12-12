<template>
    <AdminLayout>
        <div class="align-center flex w-full flex-col p-8">
            <h1 class="my-8 block text-center text-7xl">All Courses</h1>
            <div class="flex flex-row justify-between">
                <div class="flex flex-row items-center gap-2">
                    <p>View:</p>
                    <button
                        class="btn btn-sm hover:bg-neutral"
                        @click="view = 'list'"
                    >
                        <List /></button
                    ><button
                        class="btn btn-sm hover:bg-neutral"
                        @click="view = 'grid'"
                    >
                        <Grid2x2 />
                    </button>
                </div>
                <button
                    class="btn mb-6 self-end border border-primary bg-primary/30 text-primary-content hover:bg-primary/50 active:bg-primary"
                    @click="isCreateCourseModalOpen = true"
                >
                    Create New Course
                </button>
            </div>
            <div v-if="view === 'list'">
                <CoursesTable
                    :developmentCycles="page.props.developmentCycles as any"
                    :courses="page.props.courses as any[]"
                />
            </div>
            <div v-else class="grid grid-cols-3 gap-4">
                <CoursesGrid :courses="page.props.courses as any[]" />
            </div>
        </div>
    </AdminLayout>
    <CreateCourse
        v-if="isCreateCourseModalOpen"
        @create-course="saveCourse"
        @close="isCreateCourseModalOpen = false"
    />
</template>

<script setup lang="ts">
import CoursesGrid from '@/components/Admin/Courses/CoursesGrid.vue';
import CoursesTable from '@/components/Admin/Courses/CoursesTable.vue';
import CreateCourse from '@/components/Course/CreateCourse.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { Grid2x2, List } from 'lucide-vue-next';
import { ref } from 'vue';

const page = usePage();

const isCreateCourseModalOpen = ref(false);
const view = ref('list');

const saveCourse = (courseData: any) => {
    // Handle course creation logic here
    router.post('/courses', courseData);
    isCreateCourseModalOpen.value = false;
    router.get('/dashboard');
};
</script>

<style scoped></style>
