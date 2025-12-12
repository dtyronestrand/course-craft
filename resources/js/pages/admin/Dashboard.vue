<template>
    <AdminLayout>
        <div class="flex grow flex-col">
            <div class="wrapper frosted-backdrop h-full p-2">
                <div class="mb-6 grid grid-cols-4 gap-6">
                    <div
                        class="frosted-background rounded-xl border border-error/70 bg-base-100 p-6 text-error shadow-lg shadow-error/20"
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
                                <button
                                    @click="openModal(course)"
                                    class="mt-2 text-2xl text-error hover:underline"
                                >
                                    {{ course.prefix }} {{ course.number }}
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div
                        class="frosted-backdrop rounded-xl border border-primary/70 bg-base-100 p-6 text-primary shadow-lg shadow-primary/20"
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
                            class="mt-4"
                            :courseStatusCounts="statusCounts"
                        />
                    </div>
                    <div
                        class="fosted-backdrop rounded-xl border border-info/70 bg-base-100 p-6 shadow-lg shadow-info/20"
                    >
                        <div class="flex items-start justify-between">
                            <h2
                                class="text-sm font-semibold text-info uppercase"
                            >
                                Avg Course Completion Time (Days)
                            </h2>
                            <SquareChartGantt class="h-10 w-10 text-info" />
                        </div>
                        <p class="mt-4 text-2xl text-info">
                            {{ page.props.avgCompletionTime }}
                        </p>
                    </div>
                    <div
                        class="frosted-backdrop flex max-h-80 flex-col rounded-xl border border-success/70 bg-base-100 p-4 text-success shadow-lg shadow-success/20"
                    >
                        <div class="flex items-start justify-between">
                            <h2
                                class="text-sm font-semibold text-success uppercase"
                            >
                                Team Capacity
                            </h2>
                            <Users class="h-10 w-10 text-success" />
                        </div>
                        <div class="overflow-auto">
                            <TeamCapacityChart class="mt-4" />
                        </div>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div
                        class="frosted-backdrop flex-1 rounded-xl border border-neutral bg-base-100 p-6 shadow-lg shadow-neutral/20"
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
                            class="flex h-full flex-col rounded-xl border border-info/70 bg-base-100 p-6 shadow-lg shadow-info/20"
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
        <CourseDetailsModal
            v-if="isModalOpened && selectedCourse"
            :isOpen="isModalOpened"
            :course="selectedCourse"
            @modal-close="closeModal"
        />
    </AdminLayout>
</template>

<script setup lang="ts">
import CourseDetailsModal from '@/components/Admin/Courses/CourseDetailsModal.vue';
import ActivityFeed from '@/components/Admin/Dashboard/ActivityFeed.vue';
import CourseStatusChart from '@/components/Admin/Dashboard/CourseStatusChart.vue';
import ProjectPipelineTable from '@/components/Admin/Dashboard/ProjectPipelineTable.vue';
import TeamCapacityChart from '@/components/Admin/Dashboard/TeamCapacityChart.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { PageProps } from '@inertiajs/core';
import { usePage } from '@inertiajs/vue3';
import {
    ChartColumnBig,
    SquareChartGantt,
    TriangleAlert,
    Users,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

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

const statusCounts = computed(() => {
    return {
        Pending: page.props.pendingCoursesCount,
        ...page.props.courseStatusCounts,
    };
});
const isModalOpened = ref(false);
const selectedCourse = ref<any>(null);

const openModal = (course: any) => {
    selectedCourse.value = course;
    isModalOpened.value = true;
};

const closeModal = () => {
    isModalOpened.value = false;
    selectedCourse.value = null;
};
</script>

<style scoped></style>
