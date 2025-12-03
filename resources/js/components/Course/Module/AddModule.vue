<template>
    <div class="mx-32 flex gap-4 border bg-base-300 p-8">
        <form @submit.prevent="handleSubmit" class="w-full">
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
                        class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-error px-[1.5em] py-[0.25em] text-center font-bold text-error-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                        @click="form.module_objectives.splice(index, 1)"
                    >
                        Remove
                    </button>
                </li>
            </ol>
            <button
                type="button"
                class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-info px-[1.5em] py-[0.25em] text-center font-bold text-info-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
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
                    class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none self-start rounded-[0.9375em] border-[0.125em] border-secondary bg-error px-[1.5em] py-[0.25em] text-center font-bold text-error-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                    @click="form.course_assessments.splice(index, 1)"
                >
                    Remove
                </button>
            </div>

            <button
                type="button"
                class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-info px-[1.5em] py-[0.25em] text-center font-bold text-info-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
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
                <label class="my-4 label text-2xl">
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
                        form.course_instructions[index]
                            .aligned_module_objectives
                    "
                />
                <button
                    type="button"
                    class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none self-start rounded-[0.9375em] border-[0.125em] border-secondary bg-error px-[1.5em] py-[0.25em] text-center font-bold text-error-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                    @click="form.course_instructions.splice(index, 1)"
                >
                    Remove
                </button>
            </div>
            <button
                type="button"
                class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-info px-[1.5em] py-[0.25em] text-center font-bold text-info-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                @click="
                    form.course_instructions.push({
                        title: '',
                        aligned_module_objectives: [],
                    })
                "
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
                    class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none self-start rounded-[0.9375em] border-[0.125em] border-secondary bg-error px-[1.5em] py-[0.25em] text-center font-bold text-error-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                    @click="form.course_materials.splice(index, 1)"
                >
                    Remove
                </button>
            </div>

            <button
                type="button"
                class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-info px-[1.5em] py-[0.25em] text-center font-bold text-info-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
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
                        form.course_media_library_needs[index]
                            .aligned_module_objectives
                    "
                />
                <button
                    type="button"
                    class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none self-start rounded-[0.9375em] border-[0.125em] border-secondary bg-error px-[1.5em] py-[0.25em] text-center font-bold text-error-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                    @click="form.course_media_library_needs.splice(index, 1)"
                >
                    Remove
                </button>
            </div>

            <button
                type="button"
                class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-info px-[1.5em] py-[0.25em] text-center font-bold text-info-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                @click="
                    form.course_media_library_needs.push({
                        title: '',
                        aligned_module_objectives: [],
                    })
                "
            >
                Add Media/Library Need
            </button>
            <div class="mt-8 flex flex-row gap-4">
                <button
                    type="submit"
                    class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-success px-[1.5em] py-[0.25em] text-center font-bold text-success-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                >
                    Save
                </button>
                <button
                    type="button"
                    class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-error px-[1.5em] py-[0.25em] text-center font-bold text-error-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
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

import { Course_Assessment, ModuleObjective } from '@/types';
import { computed } from 'vue';
import ModuleObjectiveDropDown from './ModuleObjectiveDropDown.vue';
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
    course_instructions: [] as {
        title: string;
        aligned_module_objectives: string[];
    }[],
    course_materials: [] as {
        title: string;
        aligned_module_objectives: string[];
    }[],
    course_media_library_needs: [] as {
        title: string;
        aligned_module_objectives: string[];
    }[],
    course_id: props.course.id,
});

const objectives = computed(() => {
    const existingObjectives =
        props.course.modules
            ?.flatMap((module) => module.objectives)
            .filter((obj) => obj !== undefined) || [];
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
