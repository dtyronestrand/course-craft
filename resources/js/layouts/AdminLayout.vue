<template>
<div class="drawer lg:drawer-open">
<input id="sidebar" type="checkbox" class="drawer-toggle"/>
<div class="drawer-content flex flex-col items-center ">
<slot />
<label for="sidebar" class="btn drawer-button lg:hidden">Open Sidebar</label>
</div>
<div class="drawer-side">
<label for="sidebar" aria-label="close sidebar" class="drawer-overlay"></label>
<div class="flex flex-row align-center items-center gap-4 bg-base-200 text-base-content w-80 p-4">
<AppLogo class="m-0" />
</div>
<ul class="menu text-2xl text-base-content bg-base-200 min-h-full w-80 p-4">
    <li><Link href="/admin/dashboard"><Icon name="LayoutGrid" /><span class="px-4">Dashboard</span></Link></li>
    <li><Link href="/admin/calendar"><Icon name="Calendar"/><span class="px-4">Calendar</span></Link></li>
    <li><Link href="/admin/courses"><Icon name="Folder"/><span class="px-4">Courses</span></Link></li>
    <li><Link href="/admin/users"><Icon name="PersonStanding"/><span class="px-4">Users</span></Link></li>
    <li><Link href="/admin/settings"><Icon name="Settings"/><span class="px-4">Settings</span></Link></li>
    <li class="mt-100"><Link href="/logout"><Icon name="ArrowRightToLine"/><span class="px-4">Log Out</span></Link></li>
</ul>
</div>
</div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
import Icon from '@/components/Icon.vue';
</script>

<style scoped>

</style>