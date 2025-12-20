<template>
    <UsersTable :users="page.props.users" />
</template>

<script setup lang="ts">
import UsersTable from '@/components/Admin/Users/UsersTable.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Course } from '@/types';
import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import { usePage } from '@inertiajs/vue3';
defineOptions({ layout: AdminLayout });
interface User {
    id: number;
    first_name: string;
    last_name: string;
    courses_count: number;
    courses: Course[];
}

interface PageProps extends InertiaPageProps {
    users: User[];
}

const page = usePage<PageProps>();
</script>

<style scoped></style>
