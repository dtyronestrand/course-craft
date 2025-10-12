<template>
    <div class="flex flex-col gap-8">
    {{ props.course }}
    <h2 class="text-5xl font-bold mb-8 pl-4">Course Map</h2>
        <div v-if="props.course.modules?.length" >
<table class="min-w-[350px] w-full max-w-[1200px]  border-4 border-secondary bg-base-300 rounded-xl overflow-hidden shadow-[4px 4px 8px var(--color-base-300)] border-collapse">
<thead>
<tr>
<th>Module</th>
<th>Aligned Course Objectives</th>
<th>Module Objectives</th>
<th>Assessments</th>
<th>Instructional Activities</th>
<th>Instructional Materials</th>
<th>Media/Library Needs</th>
</tr>
</thead>
<tbody>
<tr v-for="module in props.course.modules" :key="module.id">
<td>{{ module.title }}</td>
<td>
<div v-for="objective in module.course_objectives" :key="objective.id">
{{ objective.number }}: {{ objective.objective }}
</div>
</td>
<td>
<div v-for="(objective, index) in JSON.parse(module.module_objectives)" :key="index">
{{ objective.number }}: {{ objective.objective }}
</div>
</td>
<td>{{ module.course_assessments }}</td>
<td>{{ module.course_instructions }}</td>
<td>{{ module.course_materials }}</td>
<td>{{ module.course_media_library_needs }}</td>
</tr>
</tbody>
</table>
            </div>
            <div>
            <button class="btn btn-info text-info-content" @click="addModule">Add Module</button>
            </div>
    </div>
    <AddModule v-if="addModuleModalOpen" :numberOfModules="props.numberOfModules" :course="props.course" :parent="'map'" @close="addModuleModalOpen = false" />
</template>

<script setup lang="ts">
import {ref} from "vue";
import AddModule from "@/components/Course/Module/AddModule.vue";
import { Course } from "@/types";

interface Props {
    course: Course;
    numberOfModules: number;
}
const props = defineProps<Props>();
const addModuleModalOpen = ref(false);
const addModule = () => {
    addModuleModalOpen.value = true;
}

</script>

<style scoped>
th, td{
    padding: 12px 16px;
    text-align: left;
    border-bottom: 2px solid rgba(from var(--color-neutral) R G B /0.8);
}

th{
    text-align:center;
}

thead{
    background: rgba(from var(--color-secondary) R G B /0.4);
    font-weight: bold;
    color: var(--color-secondary-content);
}

tbody tr:nth-child(even){
    background: rgba(from var(--color-base-300) R G B /0.1);
}

td{
    word-break: break-word;
}

tr td:first-child{
    word-break: keep-all;
}
</style>