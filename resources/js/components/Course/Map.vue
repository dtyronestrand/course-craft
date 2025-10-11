<template>
    <div>
        <div v-if="props.course.modules?.length" >
<table>
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
<td>{{ module.aligned_course_objectives }}</td>
<td>{{ module.learning_objectives }}</td>
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

</style>