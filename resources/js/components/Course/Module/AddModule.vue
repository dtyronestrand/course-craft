<template>
    <div class="mx-32 flex gap-4 border bg-base-300 p-8">
    
       <form
          
            @submit.prevent="handleSubmit"
            class="w-full"
        >
            <div class="mb-4 flex flex-row gap-4">
                <label class="label text-2xl" for="number"
                    >Module {{ form.number }}:</label
                >

                <input
                    type="text"
                    placeholdeer="Module Name"
                    class="input-bordered input w-full max-w-xs"
                    v-model="form.title"
                    required
                />
            </div>
            <div class="mb-4">
                <label class="label text-2xl">
                    <span class="label-text">Aligned Course Objectives:</span>
                </label>
            </div>
            <div
                class="columns-4"
                v-for="(objective, index) in props.course.objectives"
                :key="index"
            >
                <input
                    type="checkbox"
                    :value="objective.number"
                    v-model="form.course_objectives"
                    class="checkbox mr-2 checkbox-primary"
                />
                <span class="pl-4">{{ objective.number }} </span>
            </div>
            <div class="flex flex-col">
                <label class="my-4 label text-2xl">Module Objectives:</label>
            </div>
            <ol class="mb-4 list-decimal pl-8">
                <li
                    v-for="(objective, index) in form.module_objectives"
                    :key="index"
                    class="mb-2 flex flex-row flex-nowrap gap-4"
                >
                {{ form.number }}.{{ form.module_objectives[index].number }}

                    <input
                        class="input w-full pl-4"
                        type="text"
                        placeholder="Objective"
                        v-model="form.module_objectives[index].objective"
                    />
                    <button
                        type="button"
                        class="bg-error text-error-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                        @click="form.module_objectives.splice(index, 1)"
                    >
                        Remove
                    </button>
                </li>
            </ol>
            <button
                type="button"
                class="bg-info text-info-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                @click="
                    form.module_objectives.push({
                        number: `${form.module_objectives.length + 1}`,
                        objective: '',
                    })
                "
            >
                Add Objective
            </button>
            <div class="flex flex-col">
                <label class="my-4 label text-2xl">
                    <span class="label-text">Assessments:</span>
                </label>
            </div>
            <div
                v-for="(assessment, index) in form.course_assessments"
                :key="index"
                class="mb-2 flex flex-col gap-4 border p-8"
            >
                <input
                    v-model="form.course_assessments[index].title"
                    placeholder="Assessment"
                    class="input-bordered input w-full"
                />
                <label class="label mt-2">
                    <span class="label-text">Aligned Module Objectives:</span>
                </label>

                <ModuleObjectiveDropDown
                    :objectives="objectives"
                    v-model="
                        form.course_assessments[index].aligned_module_objectives
                    "
                />
                <button
                    type="button"
                  class="self-start bg-error text-error-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                    @click="form.course_assessments.splice(index, 1)"
                >
                    Remove
                </button>
            </div>

            <button
                type="button"
                class="bg-info text-info-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                @click="
                    form.course_assessments.push({
                        title: '',
                        assesment_type: '',
                        aligned_module_objectives: [],
                    })
                "
            >
                Add Assessment
            </button>
            <div class="flex flex-col">
                <label class="label my-4 text-2xl">
                    <span class="label-text">Instructional Activities:</span>
                </label>
            </div>
            <div
                v-for="(activity, index) in form.course_instructions"
                :key="index"
                class="mb-2 flex flex-col gap-4 border p-8"
            >
                <input
                    v-model="form.course_instructions[index].title"
                    placeholder="Activity"
                    class="input-bordered input w-full max-w-xs"
                />
     
            <label class="label mt-2">
                <span class="label-text">Aligned Module Objectives:</span>
            </label>

            <ModuleObjectiveDropDown
                :objectives="objectives"
                v-model="
                    form.course_instructions[index].aligned_module_objectives
                "
            />
                <button
                    type="button"
                  class="self-start bg-error text-error-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                    @click="form.course_instructions.splice(index, 1)"
                >
                    Remove
                </button>
            </div>
            <button
                type="button"
               class="bg-info text-info-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                @click="form.course_instructions.push({
                     title: '',
                     aligned_module_objectives: [],
                })"
            >
                Add Activity
            </button>
            <div class="flex flex-col">
                <label class="my-4 label text-2xl">
                    <span class="label-text">Instructional Materials:</span>
                </label>
            </div>
            <div
                v-for="(material, index) in form.course_materials"
                :key="index"
                class="mb-2 flex flex-col gap-4 border p-8"
            >
                <input
                    v-model="form.course_materials[index].title"
                    placeholder="Assessment"
                    class="input-bordered input w-full"
                />
                <label class="label mt-2">
                    <span class="label-text">Aligned Module Objectives:</span>
                </label>

                <ModuleObjectiveDropDown
                    :objectives="objectives"
                    v-model="
                        form.course_materials[index].aligned_module_objectives
                    "
                />
                <button
                    type="button"
                    class="self-start bg-error text-error-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                    @click="form.course_materials.splice(index, 1)"
                >
                    Remove
                </button>
            </div>

            <button
                type="button"
                class="bg-info text-info-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                @click="
                    form.course_materials.push({
                        title: '',
                        aligned_module_objectives: [],
                    })
                "
            >
                Add Material
            </button>
              <div class="flex flex-col">
                <label class="my-4 label text-2xl">
                    <span class="label-text">Library Media Needs:</span>
                </label>
            </div>
            <div
                v-for="(need, index) in form.course_media_library_needs"
                :key="index"
                class="mb-2 flex flex-col gap-4 border p-8"
            >
                <input
                    v-model="form.course_media_library_needs[index].title"
                    placeholder="Need"
                    class="input-bordered input w-full"
                />
                <label class="label mt-2">
                    <span class="label-text">Aligned Module Objectives:</span>
                </label>

                <ModuleObjectiveDropDown
                    :objectives="objectives"
                    v-model="
                        form.course_media_library_needs[index].aligned_module_objectives
                    "
                />
                <button
                    type="button"
                   class="self-start bg-error text-error-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                    @click="form.course_media_library_needs.splice(index, 1)"
                >
                    Remove
                </button>
            </div>

            <button
                type="button"
              class="bg-info text-info-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                @click="
                    form.course_media_library_needs.push({
                        title: '',
                        aligned_module_objectives: [],
                    })
                "
            >
                Add Media/Library Need
            </button>
            <div class="flex flex-row gap-4 mt-8">
                <button type="submit" class="bg-success text-success-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" >Save</button>
                <button
                    type="button"
                  class="bg-error text-error-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" 
                    @click="emit('close')"
                >
                    Cancel
                </button>
            </div>
        </form>
      
    </div>
</template>

<script setup lang="ts">

import { useForm } from '@inertiajs/vue3';

import ModuleObjectiveDropDown from './ModuleObjectiveDropDown.vue';
import {computed} from 'vue';
import { ModuleObjective, Course_Assessment } from '@/types';
interface Props {
    course: {
        id: number;
        objectives: {
            number: string;
            objective: string;
        }[];
        modules: {
            id: number;
            module_number: number;
            module_name: string;
            objectives: ModuleObjective[];
            aligned_course_objectives: string[];
            course_assessments: Course_Assessment[];
            course_instructions: string[];
            instructional_materials: string[];
            media_library_needs: string[];
        }[];
    };
    parent: string;
    numberOfModules: number;
}
const props = defineProps<Props>();
const emit = defineEmits(['close']);



const form = useForm({
    title: '',
    number: props.numberOfModules + 1,
    module_objectives: [] as ModuleObjective[],
    course_objectives: [],
    course_assessments: [] as Course_Assessment[],
    course_instructions: [] as {title: string; aligned_module_objectives: string[]}[],
    course_materials: [] as { title: string; aligned_module_objectives: string[] }[],
    course_media_library_needs: [] as { title: string; aligned_module_objectives: string[] }[],
    course_id: props.course.id,
});

const objectives = computed(() => {
    const existingObjectives =
        props.course.modules?.flatMap((module) => module.objectives).filter(obj => obj !== undefined) ||
        [];
    return [...existingObjectives, ...form.module_objectives];
});

const handleSubmit = () => {
    form.post('/course_modules', {
        onSuccess: () => {
            emit('close');
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<style scoped></style>
