<script setup lang="ts">
import New from '@/layouts/New.vue';

import CoursesTable from '@/components/Course/ CoursesTable.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <Head title="Dashboard" />

    <New>
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1 class="prose mb-4 text-center text-7xl">
                Course Crafter Dashboard
            </h1>

            <CoursesTable :courses="page.props.courses as any[]" />
        </div>
    </New>
</template>
<style scoped></style>
