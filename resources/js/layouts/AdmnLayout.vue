<template>
    <div class="draw lg:drawer-open">
    <input id="my-drawer-1" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col items-center justify-center">
    <label for="my-drawer-1" class="btn drawer-button lg:hidden">
        Menu
    </label>
    </div>
    <div class="drawer-side">
    <label for="my-drawer-1" aria-label="close menu" class="drawer-overlay"></label>
    <ul class="menu bg-base-200 min-h-full w-80 p-4">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li><a href="/admin/courses">Courses</a></li>
        <li><a href="/admin/users">Users</a></li>
        <li><a href="/admin/settings">Settings</a></li>
    </ul>
    </div>
    </div>
        <slot />
  


</template>

<script setup lang="ts">

</script>

<style scoped>

</style>