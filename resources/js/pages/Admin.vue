<template>
    <AdminLayout>
        <div class="mx-16 flex flex-col p-8">
            <h1 class="prose mb-8 text-7xl">Course Crafter Admin Dashboard</h1>
            <CoursesTable :courses="page.props.courses as any[]" />
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import CoursesTable from '@/components/Admin/Courses/CoursesTable.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
</script>

<style scoped></style>
