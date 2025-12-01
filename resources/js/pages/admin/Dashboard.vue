<template>
    <AdminLayout>
   <div class=" grow flex flex-col">
    <header class="flex items-center py-4 px-2 justify-between mx-12 ">
        <h1 class="text-3xl font-bold">{{ page.props.auth.user.first_name }}'s Dashboard</h1>
    <div class="flex gap-4 items-center">
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
     <div class="wrapper h-full p-2">
<div class="grid grid-cols-4 gap-6 mb-6">
<div class="glass-effect rounded-xl p-6 border border-error/70 shadow-lg shadow-error/70">
<div class="flex justify-between items-start">
<h2 class="text-sm uppercase font-semibold text-error">Needs Attention</h2>
<TriangleAlert class="text-error w-10 h-10"/></div>
<ul class="list-disc text-error list-inside mt-8">
    <li v-for="course in page.props.coursesNeedingAttention" :key="course.id">
        <Link :href="`/admin/courses/${course.id}/ `" class="text-error text-5xl mt-2 hover:underline">{{ course.prefix }} {{ course.number }}</Link> 
    </li>
    </ul>
</div>
<div class="glass-effect rounded-xl p-6 border border-primary/70 shadow-lg shadow-primary/20">
<div class="flex justify-between items-start"><h2 class="text-sm uppercase font-semibold text-primary">Project Status Distribution</h2><ChartColumnBig class="w-10 h-10 text-primary"/></div>
<p class="text-md text-primary" >Total Active Courses: {{ page.props.activeCoursesCount }}</p>
<CourseStatusChart class="flex-1 mt-4" :courseStatusCounts="statusCounts"/>
</div>
<div class="glass-effect rounded-xl p-6 border border-info/70 shadow-lg shadow-info/20">
<div class="flex justify-between items-start">
<h2 class="text-sm text-info uppercase font-semibold">
Avg Course Completion Time (Days)
</h2>
<SquareChartGantt class="w-10 h-10 text-info"/>
</div>
</div>
<div class="glass-effect rounded-xl p-4 border border-success/70 shadow-lg shadow-success/20">
<div class="flex justify-between items-start">
<h2 class="text-sm uppercase font-semibold text-success">Team Capacity</h2>
<Users class="w-10 h-10 text-success"/>
</div>
</div>
</div>
<div class="flex gap-6">
<div class="flex-1 border border-primary/70 shadow-lg shadow-primary/20 glass-effect rounded-xl p-6">
<div class="flex justify-between items-start">
<h2 class="text-xl uppercase font-semibold text-warning">Project Pipeline</h2>
<div class="flex items-center gap-4">
<div class="relative">
  <Search />
</div>
<button class="flex items-center gap-2 bg-info/20 px-4 py-2 rounded-g text-sm font-medium text-info hover:bg-info/30">Filter <ListFilter/></button>
</div>
</div>
<ProjectPipelineTable :courses="page.props.courses"/>
</div>
<aside class="w-80 shrink-0">
<div class="glass-effect rounded-xl p-6 h-full flex flex-col border border-info/70 shadow-lg shadow-info/20"><h2 class="text-xl font-semibold mb-6 text-secondary">Recent Activities</h2><ActivityFeed :initialActivities="page.props.recentActivities"/></div>
</aside>
</div>
    </div>
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
import {TriangleAlert, ChartColumnBig, SquareChartGantt, Users, ListFilter} from 'lucide-vue-next';

import ActivityFeed from '@/components/Admin/Dashboard/ActivityFeed.vue';
import CourseStatusChart from '@/components/Admin/Dashboard/CourseStatusChart.vue';
import ProjectPipelineTable from '@/components/Admin/Dashboard/ProjectPipelineTable.vue';
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
    coursesByPrefix: Record<string, number>;
    coursesNeedingAttention: any[];
    courses: any[];
}>();
const { getInitials } = useInitials();
const logout = ()=>{
  router.post('/logout');
}
const statusCounts = computed(()=>{
 return {'Pending': page.props.pendingCoursesCount, ...page.props.courseStatusCounts};
})


</script>

<style scoped>



</style>