<script setup lang="ts">
</script>

<template>
    <div>
        <!-- PageHeading -->
        <header class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <div class="flex flex-col gap-1">
                <h1 class="text-base-content text-3xl font-bold leading-tight tracking-tight">Instructional Design Dashboard</h1>
                <p class="text-base-content/70 text-base font-normal leading-normal">Overview of all ongoing projects and team metrics.</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Chips -->
                <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-base-200 px-4">
                    <p class="text-base-content text-sm font-medium leading-normal">This Semester</p>
                    <span class="material-symbols-outlined text-base-content text-lg">expand_more</span>
                </button>
                <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-base-200 px-4">
                    <p class="text-base-content text-sm font-medium leading-normal">Last 30 Days</p>
                    <span class="material-symbols-outlined text-base-content text-lg">expand_more</span>
                </button>
            </div>
        </header>
        <!-- Stats -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="flex flex-col gap-2 rounded-xl p-6 bg-base-100 border border-base-300">
                <p class="text-base-content text-base font-medium leading-normal">Total Active Projects</p>
                <p class="text-base-content tracking-tight text-3xl font-bold leading-tight">42</p>
                <p class="text-success text-base font-medium leading-normal flex items-center gap-1"><span class="material-symbols-outlined text-lg">arrow_upward</span>+2%</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 bg-base-100 border border-base-300">
                <p class="text-base-content text-base font-medium leading-normal">Courses Launched</p>
                <p class="text-base-content tracking-tight text-3xl font-bold leading-tight">18</p>
                <p class="text-success text-base font-medium leading-normal flex items-center gap-1"><span class="material-symbols-outlined text-lg">arrow_upward</span>+5%</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 bg-base-100 border border-base-300">
                <p class="text-base-content text-base font-medium leading-normal">Avg. Project Cycle</p>
                <p class="text-base-content tracking-tight text-3xl font-bold leading-tight">28 Days</p>
                <p class="text-error text-base font-medium leading-normal flex items-center gap-1"><span class="material-symbols-outlined text-lg">arrow_downward</span>-3%</p>
            </div>
        </section>
        <!-- Charts and Upcoming Deadlines -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Projects at a Glance (Donut Chart) -->
                <div class="flex flex-col gap-4 rounded-xl border border-base-300 p-6 bg-base-100">
                    <h3 class="text-base-content text-lg font-semibold leading-normal">Projects at a Glance</h3>
                    <div class="flex items-center justify-center h-full min-h-[200px] relative">
                        <svg class="transform -rotate-90" height="180" viewbox="0 0 200 200" width="180">
                            <circle cx="100" cy="100" fill="transparent" r="80" class="stroke-primary" stroke-dasharray="251.2" stroke-dashoffset="0" stroke-width="25"></circle>
                            <circle cx="100" cy="100" fill="transparent" r="80" class="stroke-warning" stroke-dasharray="251.2" stroke-dashoffset="-125.6" stroke-width="25"></circle>
                            <circle cx="100" cy="100" fill="transparent" r="80" class="stroke-success" stroke-dasharray="251.2" stroke-dashoffset="-188.4" stroke-width="25"></circle>
                            <circle cx="100" cy="100" fill="transparent" r="80" class="stroke-info" stroke-dasharray="251.2" stroke-dashoffset="-213.52" stroke-width="25"></circle>
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="text-3xl font-bold text-base-content">42</span>
                            <span class="text-sm text-base-content/70">Total Projects</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 mt-4 text-sm">
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-primary"></span><span class="text-base-content/70">In Progress (21)</span></div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-warning"></span><span class="text-base-content/70">Under Review (10)</span></div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-success"></span><span class="text-base-content/70">Completed (5)</span></div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-info"></span><span class="text-base-content/70">Planning (6)</span></div>
                    </div>
                </div>
                <!-- Team Workload Summary (Bar Chart) -->
                <div class="flex flex-col gap-4 rounded-xl border border-base-300 p-6 bg-base-100">
                    <h3 class="text-base-content text-lg font-semibold leading-normal">Team Workload Summary</h3>
                    <div class="flex flex-col gap-4 pt-2">
                        <div class="grid grid-cols-[80px_1fr_auto] items-center gap-3 text-sm">
                            <span class="truncate text-base-content/70">A. Chen</span>
                            <div class="w-full bg-base-200 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="font-medium text-base-content">5</span>
                        </div>
                        <div class="grid grid-cols-[80px_1fr_auto] items-center gap-3 text-sm">
                            <span class="truncate text-base-content/70">B. Singh</span>
                            <div class="w-full bg-base-200 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 70%"></div>
                            </div>
                            <span class="font-medium text-base-content">4</span>
                        </div>
                        <div class="grid grid-cols-[80px_1fr_auto] items-center gap-3 text-sm">
                            <span class="truncate text-base-content/70">C. Rodriguez</span>
                            <div class="w-full bg-base-200 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 100%"></div>
                            </div>
                            <span class="font-medium text-base-content">6</span>
                        </div>
                        <div class="grid grid-cols-[80px_1fr_auto] items-center gap-3 text-sm">
                            <span class="truncate text-base-content/70">D. Miller</span>
                            <div class="w-full bg-base-200 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 50%"></div>
                            </div>
                            <span class="font-medium text-base-content">3</span>
                        </div>
                        <div class="grid grid-cols-[80px_1fr_auto] items-center gap-3 text-sm">
                            <span class="truncate text-base-content/70">E. Kim</span>
                            <div class="w-full bg-base-200 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 65%"></div>
                            </div>
                            <span class.font-medium text-base-content>4</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Upcoming Deadlines -->
            <div class="flex flex-col gap-4 rounded-xl border border-base-300 p-6 bg-base-100">
                <h3 class="text-base-content text-lg font-semibold leading-normal">Upcoming Deadlines</h3>
                <div class="flex flex-col gap-4 -mx-2">
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-base-200">
                        <div class="flex flex-col">
                            <p class="font-medium text-base-content text-sm">BIO-101 Course Redesign</p>
                            <p class="text-base-content/70 text-xs">Assigned to: A. Chen</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-error">2 Days</p>
                            <span class="inline-flex items-center rounded-full bg-error/20 px-2 py-0.5 text-xs font-medium text-error">Overdue</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-base-200">
                        <div class="flex flex-col">
                            <p class="font-medium text-base-content text-sm">ART-230 Module Development</p>
                            <p class="text-base-content/70 text-xs">Assigned to: D. Miller</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-warning">5 Days</p>
                            <span class="inline-flex items-center rounded-full bg-warning/20 px-2 py-0.5 text-xs font-medium text-warning">At Risk</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-base-200">
                        <div class="flex flex-col">
                            <p class="font-medium text-base-content text-sm">CS-350 Project Scoping</p>
                            <p class="text-base-content/70 text-xs">Assigned to: C. Rodriguez</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-success">10 Days</p>
                            <span class="inline-flex items-center rounded-full bg-success/20 px-2 py-0.5 text-xs font-medium text-success">On Track</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-base-200">
                        <div class="flex flex-col">
                            <p class="font-medium text-base-content text-sm">ENG-112 Interactive Syllabus</p>
                            <p class="text-base-content/70 text-xs">Assigned to: B. Singh</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-success">14 Days</p>
                            <span class="inline-flex items-center rounded-full bg-success/20 px-2 py-0.5 text-xs font-medium text-success">On Track</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-base-200">
                        <div class="flex flex-col">
                            <p class="font-medium text-base-content text-sm">PHY-401 Lab Simulation</p>
                            <p class="text-base-content/70 text-xs">Assigned to: E. Kim</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-success">21 Days</p>
                            <span class="inline-flex items-center rounded-full bg-success/20 px-2 py-0.5 text-xs font-medium text-success">On Track</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .material-symbols-outlined {
      font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24
    }
</style>
