<template>
<AdminLayout>
<div class="flex flex-col w-full mx-16 p-8">
            <h1 class="text-7xl  block text-center mb-8">All Courses</h1>
  <CoursesTable  :courses="page.props.courses as any[]"/>
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