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
      <li><Link href="/admin/settings">Settings</Link></li>
      <li><button class="btn btn-ghost" @click="logout">Logout</button></li>
</ul>
  </details> 
    </div>
    </header>
        <h1 class="text-7xl text-center mx-auto p-4 my-8 prose">{{ page.props.auth.user.first_name }}'s Dashboard</h1>
<div class="grid grid-cols-4 grid-rows-13 gap-4 mx-12">
<InfoCard title="Active Courses" :info="page.props.activeCoursesCount"/>
<InfoCard title="Courses Needing Attention" :info="page.props.activeCoursesCount"/>
<div class="col-span-2 row-span-2 bg-base-100 border border-primary text-neutral-content text-3xl rounded-2xl p-4 flex flex-col justify-between gap-8"><h2>Project Status Distribution</h2>
<CourseStatusChart class="flex-1" :courseStatusCounts="statusCounts"/>
</div>
<div class="col-span-2 row-span-2 bg-base-100 text-base-content border border-primary text-3xl rounded-2xl p-4"><h2>Recent Activities</h2><ActivityFeed :initialActivities="page.props.recentActivities"/></div>
<div class="col-span-2 row-span-2 bg-base-100 text-base-content border border-primary text-3xl rounded-2xl p-4"><h2>Courses By Department</h2></div>
</div>

</AdminLayout>
</template>

<script setup lang="ts">

import { usePage, Link , router} from '@inertiajs/vue3';
import type { PageProps } from '@inertiajs/core';
import AdminLayout from '@/layouts/AdminLayout.vue';
import Search from '@/components/Search.vue';
import { useInitials } from '@/composables/useInitials';
import { computed } from 'vue';
import NotificationCenter from '@/components/NotificationCenter.vue';
import InfoCard from '@/components/Admin/Dashboard/InfoCard.vue';
import ActivityFeed from '@/components/Admin/Dashboard/ActivityFeed.vue';
import CourseStatusChart from '@/components/Admin/Dashboard/CourseStatusChart.vue';
interface Activity {
    id: number;
    user: {name: string; initials: string;};
    action: string;
    description: string;
    timestamp: string;
    created_at: string;
}

const page = usePage<PageProps & {
    activeCoursesCount: number;
    courseStatusCounts: Record<string, number>;
    pendingCoursesCount: number;
    recentActivities: Activity[];
    statusCounts: Record<string, number>;
}>();
const { getInitials } = useInitials();
const logout = ()=>{
  router.post('/logout');
}
const statusCounts = computed(()=>{
 return {...page.props.courseStatusCounts, 'Pending': page.props.pendingCoursesCount};
})
</script>

<style scoped>

</style>