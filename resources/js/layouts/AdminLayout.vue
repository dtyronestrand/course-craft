<template>
    <AppShell variant="sidebar">
    <AppSidebar/>
    <label for="my-drawer-1" class="btn drawer-button lg:hidden">
        Menu
    </label>
    <label for="my-drawer-1" aria-label="close menu" class="drawer-overlay"></label>
    <ul class="menu bg-base-200 min-h-full w-80 p-4">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li><a href="/admin/courses">Courses</a></li>
        <li><a href="/admin/users">Users</a></li>
        <li><a href="/admin/settings">Settings</a></li>
    </ul>
 
        <slot />
  

</AppShell>
</template>

<script setup lang="ts">
import AppShell from '@/components/AppShell.vue';


</script>

<style scoped>

</style>