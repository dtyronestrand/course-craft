<template>
    <AdminLayout>
        <h1 class="text-7xl text-center mx-auto p-4 my-8 prose">{{ page.props.auth.user.first_name }}'s Dashboard</h1>

</AdminLayout>
</template>

<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
const page = usePage();
</script>

<style scoped>

</style>