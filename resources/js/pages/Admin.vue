<template>
<AdminLayout>
<div class="flex flex-col mx-16 p-8">
            <h1 class="text-7xl prose mb-8">Course Crafter Admin Dashboard</h1>
       <CoursesTable :courses="page.props.courses as any[]" />
</div>

</AdminLayout>



</template>

<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import CoursesTable from '@/components/Admin/Courses/CoursesTable.vue';
const page = usePage();
</script>

<style scoped>

</style>