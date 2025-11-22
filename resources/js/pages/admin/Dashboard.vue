<template>
    <AdminLayout>
    <header class="flex flex-row px-8 justify-between mx-12 ">
    <div>
    <Search />
    </div>
    <div class="flex flex-row gap-4 items-center">
    <div class="flex flex-row gap-2 items-center">
    <NotificationCenter />
    </div>
  <details class="dropdown">
  <summary class="rounded-full text-center bg-primary text-primary-content w-8 h-8 flex items-center justify-center font-bold">
    {{ getInitials(page.props.auth.user.first_name, page.props.auth.user.last_name) }}
    </summary>
    <ul class="menu dropdown-content bg-base-100 rounded-box z-1 -left-20 w-42 p-2 shadow-sm">
      <li><a>Profile</a></li>
      <li><a>Settings</a></li>
      <li><a>Logout</a></li>
</ul>
  </details> 
    </div>
    </header>
        <h1 class="text-7xl text-center mx-auto p-4 my-8 prose">{{ page.props.auth.user.first_name }}'s Dashboard</h1>
<div class="grid grid-cols-4 grid-rows-13">
<InfoCard title="Active Courses" :info="page.props.activeCoursesCount"/>
</div>
</AdminLayout>
</template>

<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import Search from '@/components/Search.vue';
import { useInitials } from '@/composables/useInitials';
import NotificationCenter from '@/components/NotificationCenter.vue';
import InfoCard from '@/components/Admin/Dashboard/InfoCard.vue';
const page = usePage();
const { getInitials } = useInitials();
</script>

<style scoped>

</style>