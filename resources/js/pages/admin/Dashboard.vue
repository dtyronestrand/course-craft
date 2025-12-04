<template>
    <AdminLayout>
        <div class="flex grow flex-col">
            <header class="mx-12 flex items-center justify-between px-2 py-4">
                <h1 class="text-3xl font-bold">
                    {{ page.props.auth.user.first_name }}'s Dashboard
                </h1>
                <div class="flex items-center gap-4">
                    <div class="flex flex-row items-center gap-2">
                        <NotificationCenter />
                    </div>
                    <details class="dropdown">
                        <summary
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-center font-bold text-primary-content"
                        >
                            {{
                                getInitials(
                                    page.props.auth.user.first_name,
                                    page.props.auth.user.last_name,
                                )
                            }}
                        </summary>
                        <ul
                            class="dropdown-content menu -left-20 z-1 w-42 rounded-box bg-base-100 p-2 shadow-sm"
                        >
                            <li><a>Profile</a></li>
                            <li>
                                <Link href="/admin/settings">Settings</Link>
                            </li>
                            <li>
                                <button class="btn btn-ghost" @click="logout">
                                    Logout
                                </button>
                            </li>
                        </ul>
                    </details>
                </div>
            </header>
       
            <div class="wrapper frosted-backdrop h-full p-2">
                <div class="mb-6 grid grid-cols-4 gap-6">
                    <div
                        class="glass-effect rounded-xl border border-error/70 p-6 shadow-lg shadow-error/20"
                    >
                        <div class="flex items-start justify-between">
                            <h2
                                class="text-sm font-semibold text-error uppercase"
                            >
                                Needs Attention
                            </h2>
                            <TriangleAlert class="h-10 w-10 text-error" />
                        </div>
                        <ul class="mt-8 list-inside list-disc text-error">
                            <li
                                v-for="course in page.props
                                    .coursesNeedingAttention"
                                :key="course.id"
                            >
                                <Link
                                    :href="`/admin/courses/${course.id}/ `"
                                    class="mt-2 text-5xl text-error hover:underline"
                                    >{{ course.prefix }}
                                    {{ course.number }}</Link
                                >
                            </li>
                        </ul>
                    </div>
                    <div
                        class="glass-effect rounded-xl border border-primary/70 p-6 shadow-lg shadow-primary/20"
                    >
                        <div class="flex items-start justify-between">
                            <h2
                                class="text-sm font-semibold text-primary uppercase"
                            >
                                Project Status Distribution
                            </h2>
                            <ChartColumnBig class="h-10 w-10 text-primary" />
                        </div>
                        <p class="text-md text-primary">
                            Total Active Courses:
                            {{ page.props.activeCoursesCount }}
                        </p>
                        <CourseStatusChart
                            class="mt-4 flex-1"
                            :courseStatusCounts="statusCounts"
                        />
                    </div>
                    <div
                        class="glass-effect rounded-xl border border-info/70 p-6 shadow-lg shadow-info/20"
                    >
                        <div class="flex items-start justify-between">
                            <h2
                                class="text-sm font-semibold text-info uppercase"
                            >
                                Avg Course Completion Time (Days)
                            </h2>
                            <SquareChartGantt class="h-10 w-10 text-info" />
                        </div>
                        <p class="mt-4 text-5xl text-info">
                            {{ page.props.avgCompletionTime }}
                        </p>
                    </div>
                    <div
                        class="glass-effect rounded-xl border border-success/70 p-4 shadow-lg shadow-success/20"
                    >
                        <div class="flex items-start justify-between">
                            <h2
                                class="text-sm font-semibold text-success uppercase"
                            >
                                Team Capacity
                            </h2>
                            <Users class="h-10 w-10 text-success" />
                        </div>
                        <TeamCapacityChart class="mt-4 flex-1" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div
                        class="glass-effect flex-1 rounded-xl border border-primary/70 p-6 shadow-lg shadow-primary/20"
                    >
                        <div class="flex items-start justify-between">
                            <h2
                                class="text-xl font-semibold text-warning uppercase"
                            >
                                Project Pipeline
                            </h2>
                            <div class="flex items-center gap-4">
                                <div class="relative"></div>
                            </div>
                        </div>
                        <ProjectPipelineTable :courses="page.props.courses" />
                    </div>
                    <aside class="w-80 shrink-0">
                        <div
                            class="glass-effect flex h-full flex-col rounded-xl border border-info/70 p-6 shadow-lg shadow-info/20"
                        >
                            <h2
                                class="mb-6 text-xl font-semibold text-secondary"
                            >
                                Recent Activities
                            </h2>
                            <ActivityFeed
                                :initialActivities="page.props.recentActivities"
                            />
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import NotificationCenter from '@/components/NotificationCenter.vue';
import { useInitials } from '@/composables/useInitials';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { PageProps } from '@inertiajs/core';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    ChartColumnBig,
    SquareChartGantt,
    TriangleAlert,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';

import ActivityFeed from '@/components/Admin/Dashboard/ActivityFeed.vue';
import CourseStatusChart from '@/components/Admin/Dashboard/CourseStatusChart.vue';
import ProjectPipelineTable from '@/components/Admin/Dashboard/ProjectPipelineTable.vue';
import TeamCapacityChart from '@/components/Admin/Dashboard/TeamCapacityChart.vue';
interface Activity {
    id: number;
    user: { name: string; initials: string };
    action: string;
    description: string;
    timestamp: string;
    created_at: string;
}

const page = usePage<
    PageProps & {
        activeCoursesCount: number;
        courseStatusCounts: Record<string, number>;
        pendingCoursesCount: number;
        recentActivities: Activity[];
        statusCounts: Record<string, number>;
    
        coursesNeedingAttention: any[];
        avgCompletionTime: string;
        courses: any[];
    }
>();
const { getInitials } = useInitials();
const logout = () => {
    router.post('/logout');
};
const statusCounts = computed(() => {
    return {
        Pending: page.props.pendingCoursesCount,
        ...page.props.courseStatusCounts,
    };
});
</script>

<style scoped></style>
