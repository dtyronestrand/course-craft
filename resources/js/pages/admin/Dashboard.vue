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
     <div class="wrapper">
        <h1 class="text-7xl text-center mx-auto p-4 my-8 prose">{{ page.props.auth.user.first_name }}'s Dashboard</h1>
<div class="grid grid-cols-4 gap-4 mx-12 ">

<div class="text-xl col-span-2 bg-white/20 border-accent backdrop-blur-sm border-2 text-base-content rounded-xl p-4 ">
<h2>Courses Needing Attention</h2>
<ul class="list-disc list-inside mt-8">
    <li v-for="course in page.props.coursesNeedingAttention" :key="course.id">
        <Link :href="`/admin/courses/${course.id}/ `" class="text-primary hover:underline">{{ course.prefix }} {{ course.number }}</Link> 
    </li>
    </ul>
</div>
<div class="text-xl col-span-2 bg-base-100/30 border-accent backdrop-blur-md border-2 text-base-content  rounded-xl p-4 "><h2>Project Status Distribution</h2>
<p class="text-md">Total Active Courses: {{ page.props.activeCoursesCount }}</p>
<CourseStatusChart class="flex-1" :courseStatusCounts="statusCounts"/>
</div>
<div class="col-span-2  bg-base-100 text-base-content border border-primary text-xl rounded-2xl p-4"><h2>Recent Activities</h2><ActivityFeed :initialActivities="page.props.recentActivities"/></div>
<div class="col-span-2 bg-base-100 text-base-content border border-primary text-xl rounded-2xl p-4"><h2>Courses By Department</h2><CoursesByDepartmentChart :coursesByDepartment="page.props.coursesByPrefix"/></div>
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
import CoursesByDepartmentChart from '@/components/Admin/Dashboard/CoursesByDepartmentChart.vue';
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
    coursesByPrefix: Record<string, number>;
    coursesNeedingAttention: any[];
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


.wrapper{


background: radial-gradient(at 88% 4%, #962720 0px, transparent 50%), radial-gradient(at 62% 12%, #77231b 0px, transparent 50%), radial-gradient(at 66% 50%, #591d17 0px, transparent 50%) #ffcf00;;
mix-blend-mode: var(--_gradient-blend-mode);

    

}
.frosted-backdrop {
backdrop-filter: blur(var(--_gradient-blur)) contrast(100%) brightness(100%);
-webkit-backdrop-filter: blur(var(--_gradient-blur)) contrast(100%) brightness(100%);
}
</style>