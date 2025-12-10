<template>
    <AdminLayout>
     
        <UsersTable :users="page.props.users" />
    </AdminLayout>
</template>

<script setup lang="ts">

import AdminLayout from '@/layouts/AdminLayout.vue';
import { usePage } from '@inertiajs/vue3';
import UsersTable from '@/components/Admin/Users/UsersTable.vue';
import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import { Course } from '@/types';

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
