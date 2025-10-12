<template>
    <New>
    <div class="p-12  mx-32 bg-base-100 text-base-content">
    <div class="bg-primary border p-8 ">
<h1 class="text-7xl text-base-content mb-4"> {{ page.props.course.prefix }} {{ page.props.course.number }} </h1>
<h2 class="text-5xl mb-4 text-base-content ">{{ page.props.course.title }}</h2>
<div class="flex flex-row gap-4 mb-4 ">
<Map @changeDisplay="handleDisplay($event)"/>
<Storyboard @changeDisplay="handleDisplay($event)"/>
<Delete @delete="handleDelete"/>
</div>
    </div>
    <div class=" border  p-8">
 <div v-for="(user, index) in page.props.course.users" :key="index">
 <p>{{ user.pivot.role }}: {{ user.first_name }} {{ user.last_name  }}</p>
 </div>
 <h3 class="text-3xl my-4">Learning Objectives:</h3>
 <div v-for="(objective,index) in page.props.course.objectives" :key="index">
 <p class="text-lg">{{ objective.number }}. {{ objective.objective }}</p>
    </div>
    </div>
    </div>
    <div>
    <component :is="currentDisplay" :numberOfModules="page.props.numberOfModules"   :course="page.props.course" v-if="currentDisplay"/>
    </div>
    </New>
</template>

<script setup lang="ts">
import { usePage , router} from '@inertiajs/vue3';
import {Storyboard, Map, Delete} from '@/components/CourseActions'
import { shallowRef , defineAsyncComponent} from 'vue';
import New from '@/layouts/New.vue';
interface Course {
    id: number;
    prefix: string;
    number: string | number;
    title: string;
    objectives: { number: string; objective: string; }[];
    users: { id: number; first_name: string; last_name: string; pivot: { role: string; } }[];
    course_modules: any[];
}

interface PageProps {
    [key: string]: unknown;
    name: string;
    quote: { message: string; author: string; };
    auth: any;
    sidebarOpen: boolean;
    course: Course;
    numberOfModules: number;
}
import type { Component } from 'vue';
const currentDisplay = shallowRef<Component | null>(null);
const page = usePage<PageProps>();
const  handleDisplay = (display: string) => {
    if (display === 'map'){
        currentDisplay.value = defineAsyncComponent(() => import('@/components/Course/Map.vue'));
    } else if (display === 'storyboard'){
        currentDisplay.value = defineAsyncComponent(() => import('@/components/Course/Storyboard.vue'));
    }
};
const handleDelete = () => {
    if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
router.delete(`/courses/${page.props.course.id}`);
    }
};
</script>

<style scoped>

</style>