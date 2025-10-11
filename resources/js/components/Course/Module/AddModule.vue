<template>
    <div class="mx-32 flex  border p-8 bg-base-300  gap-4 ">
        <form v-if="props.parent==='map'" @submit.prevent="form.post('/modules')">
           <div class="mb-4 flex flex-row gap-4">
           <label class="label text-2xl" for="moduleNumber">Module {{ form.moduleNumber }}:</label>
     
                    <input type="text" placeholdeer="Module Name" class="input input-bordered w-full max-w-xs" v-model="form.moduleName" required>
           </div>
           <div class="mb-4">
                <label class="label text-2xl">
                    <span class="label-text">Aligned Course Objectives:</span>
                </label>
</div>
                <div  class=" columns-4 " v-for="(objective, index) in props.course.objectives" :key="index" >
                    <input type="checkbox" :value="objective.number" v-model="form.alignedCourseObjectives" class="checkbox checkbox-primary mr-2">
                    <span class="pl-4">{{ objective.number }} </span>
              </div>
              <div class="flex flex-col">
                <label class="label my-4 text-2xl">Module Objectives:</label>
                <ol class="list-decimal pl-8 mb-4">
                <li v-for="(objective, index) in form.moduleObjectives" :key="index" class="flex flex-row gap-4 flex-nowrap mb-2">
             
                {{ form.moduleObjectives[index].number }}
       
                   <input class="input pl-4 w-full" type="text" placeholder="Objective" v-model="form.moduleObjectives[index].objective" > 
                   <button type="button" class="btn btn-sm btn-error ml-2" @click="form.moduleObjectives.splice(index, 1)">Remove</button>
                </li>
                </ol>
                <button type="button" class="btn btn-sm w-max btn-info mt-4 mb-8" @click="form.moduleObjectives.push({ number: `${form.moduleNumber}.${form.moduleObjectives.length + 1}`, objective: '' })">Add Objective</button>
                </div>
                <div class="flex flex-col">
                <label class="label text-2xl my-4">
                    <span class="label-text">Assessments:</span>
                </label>
                </div>
                <div v-for="(assessment, index) in form.course_assessments" :key="index" class="border  p-8 mb-2 flex flex-col gap-4">
                    <input v-model="form.course_assessments[index].title" placeholder="Assessment" class="input input-bordered w-full ">
                    <label class="label mt-2">
                        <span class="label-text">Aligned Module Objectives:</span>
                        </label>
                        
                    <ModuleObjectiveDropDown
                        :objectives="objectives"
                        v-model="form.course_assessments[index].aligned_module_objectives"
                    />
                          <button type="button" class="btn btn-sm btn-error text-error-content w-24" @click="form.course_assessments.splice(index, 1)">Remove     </button>           </div>
          
                <button type="button" class="btn btn-sm btn-info text-info-content my-4" @click="form.course_assessments.push({ title: '', assesment_type: '', aligned_module_objectives: [] })">Add Assessment</button>
                <div class="mb-4 mt-4">
                <label class="label text-2xl">
                    <span class="label-text">Instructional Activities:</span>
                </label>
                <div v-for="(activity, index) in form.instructional_activities" :key="index" class="mb-2">
                    <input v-model="form.instructional_activities[index]" placeholder="Activity" class="input input-bordered w-full max-w-xs">
                    <button type="button" class="btn btn-sm btn-error ml-2" @click="form.instructional_activities.splice(index, 1)">Remove</button>
                </div>
                <button type="button" class="btn btn-sm btn-info text-info-content mb-4" @click="form.instructional_activities.push('')">Add Activity</button>
                </div>
                <div class="flex flex-row gap-4">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-error" @click="emit('close')">Cancel</button>
                </div>
                </form>
                <form v-if="props.parent==='storyboard'" @submit.prevent="form.post('/modules')">
                    <div class="flex flex-row gap-4">
                        <p>Module:</p>
                        <input type="number" class="input input-bordered w-full max-w-xs" v-model="form.moduleNumber" required>
                    </div>
                    <label class="label">
                        <span class="label-text">Module Name</span>
                    </label>
                    <input type="text" placeholdeer="Module Name" class="input input-bordered w-full max-w-xs" v-model="form.moduleName" required>
                    <label class="label">
                        <span class="label-text">Aligned CourseeObjectives</span>
                    </label>
                    <div v-for ="(objective,index) in courseObjectives" :key="index" class="mb-2">
                    <input type="checkbox" :value="objective.number" v-model="form.alignedCourseObjectives" class="checkbox checkbox-primary mr-2">
                    </div>
                        <span class="label-text">Module Objectives</span>
                
                <div v-for="(objective, index) in form.moduleObjectives" :key="index" class="mb-2">
                    <input type="text" v-model="form.moduleObjectives[index]" placeholder="Module Objective" class="input input-bordered w-full max-w-xs">
                    <button type="button" class="btn btn-sm btn-error ml-2" @click="form.moduleObjectives.splice(index, 1)">Remove</button>
                    </div>
                <button type="button" class="btn btn-sm btn-primary mb-4" @click="form.moduleObjectives.push({ number: '', objective: '' })">Add Objective</button>

                    </form>
    </div>
</template>

<script setup lang="ts">
import {ref, computed} from "vue";
import { useForm } from "@inertiajs/vue3";
import { Course_Assessment, ModuleObjective } from "@/types";
import ModuleObjectiveDropDown from "./ModuleObjectiveDropDown.vue";

interface Props{
    course: {
        id: number;
        objectives: {
            number: string;
            text: string;
        }[];
        modules: {
            id: number;
            module_number: number;
            module_name: string;
            module_objectives: ModuleObjective[];
            aligned_course_objectives: string[];
            course_assessments: Course_Assessment[];
            instructional_activities: string[];
            instructional_materials: string[];
            media_library_needs: string[];
        }[];
    };
    parent: string;
    numberOfModules: number;
}

const props = defineProps<Props>();
const emit = defineEmits(['close',] );

const courseObjectives = ref(props.course.objectives)
const objectives = computed(() => {
    const existingObjectives = props.course.modules?.flatMap(module => module.module_objectives) || [];
    return [...existingObjectives, ...form.moduleObjectives];
});


const form = useForm({
    moduleName: '',
    moduleNumber: props.numberOfModules + 1,
    moduleObjectives: [] as ModuleObjective[],
    alignedCourseObjectives: [],
    course_assessments: [] as Course_Assessment[],
    instructional_activities: [] as string[],
    instructionalMaterials: [],
    mediaLibraryNeeds: [],
    course_id: props.course.id,
    

})

</script>

<style scoped>

</style>