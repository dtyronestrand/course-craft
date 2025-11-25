<script setup lang="ts">
import New from '@/layouts/New.vue';

import { Head } from '@inertiajs/vue3';
import CoursesTable from '@/components/Course/ CoursesTable.vue';
import {usePage} from '@inertiajs/vue3';




const page = usePage();





</script>

<template>
    <Head title="Dashboard" />

    <New>
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
       <h1 class="mb-4 text-center text-7xl prose ">Course Crafter Dashboard</h1>
         
             <CoursesTable :courses="page.props.courses as any[]" />
                </div>


    </New>
   
</template>
<style scoped>

</style>