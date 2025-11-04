<template>
    <div class="flex flex-col border  border-secondary p-4 mb-8 gap-8">
    <h2 class="text-5xl font-bold mb-8 pl-4">Course Map</h2>
        <div v-if="props.course.modules?.length" >
<table class="w-full nowrap border-collapse border-2 border-secondary mt-16 mx-auto glass">

<thead>
<tr>
<th>Module</th>
<th>Aligned Course Objectives</th>
<th>Module Objectives</th>
<th>Assessments</th>
<th>Instructional Activities</th>
<th>Instructional Materials</th>
<th>Media/Library Needs</th>
<th> </th>
</tr>
</thead>
<tbody>

<tr class="" v-for="module in props.course.modules" :key="module.id">
<td class="sticky">{{module.order_index}}: &nbsp; {{ module.title }}</td>
<td>
<div v-for="objective in module.course_objectives" :key="objective.id">
{{ objective.number }}
</div>
</td>
<td>
<div v-for="(objective, index) in module.module_objectives" :key="index">
{{ objective.number }}: {{ objective.objective }}
</div>
</td>
<td><ul v-for="(assessment,index) in module.assessments" :key="index">
<li >{{ assessment.title}}</li> <li v-for="objective in assessment.objectives" :key="objective.id">{{ module.order_index }}.{{ objective.number }} </li>
</ul>
</td>
<td>{{ module.course_instructions }}</td>
<td>{{ module.course_materials }}</td>
<td>{{ module.course_media_library_needs }}</td>
<td class="flex flex-col"><button @click="editModule(module)" class="btn btn-sm btn-info ">Edit</button></td>
<td class="flex flex-col"><button @click="deleteModule(module)" class="btn btn-sm btn-error ">Delete</button></td>
</tr>
</tbody>
</table>
            </div>
            <div>
            <button
                :style="{ display: addModuleModalOpen ? 'none' : '' }"
                class="btn btn-info text-info-content"
                @click="addModule"
            >Add Module</button>
            </div>
                <AddModule v-if="addModuleModalOpen" :numberOfModules="props.numberOfModules" :course="props.course" :parent="'map'" @close="addModuleModalOpen = false" />
    <EditModule v-if="isEditing" :module="moduleToEdit" :course="props.course" :parent="'map'" :numberOfModules="props.numberOfModules" @close="() => isEditing = false" />
    </div>

</template>

<script setup lang="ts">
import {ref} from "vue";
import AddModule from "@/components/Course/Module/AddModule.vue";
import EditModule from "@/components/Course/Module/EditModule.vue";
import { Course, CourseModule } from "@/types";
import { router } from "@inertiajs/vue3";

interface Props {
    course: Course;
    numberOfModules: number;
}
const props = defineProps<Props>();
const addModuleModalOpen = ref(false);
const addModule = () => {
    addModuleModalOpen.value = true;
}

const isEditing = ref(false);
const moduleToEdit = ref<CourseModule | null>(null);
const editModule = (module: CourseModule) => {
    isEditing.value = true;
    moduleToEdit.value = module;
}
const deleteModule = (module: CourseModule) => {
    if (confirm('Are you sure you want to delete this module?')) {
        router.delete(`/course_modules/${module.id}`);
    }
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
    background: var(--color-primary);
    font-weight: bold;
    color: var(--color-primary-content);
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