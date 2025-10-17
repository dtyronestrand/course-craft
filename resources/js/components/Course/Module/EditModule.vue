<template>
    <div class="mx-32 flex gap-4 border bg-base-300 p-8">
        
        <form
            v-if="props.parent === 'map'"
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
                <ol class="mb-4 list-decimal pl-8">
                    <li
                        v-for="(objective, index) in form.module_objectives"
                        :key="index"
                        class="mb-2 flex flex-row flex-nowrap gap-4"
                    >
                        {{ form.module_objectives[index].number }}

                        <input
                            class="input w-full pl-4"
                            type="text"
                            placeholder="Objective"
                            v-model="form.module_objectives[index].objective"
                        />
                        <button
                            type="button"
                            class="btn ml-2 btn-sm btn-error"
                            @click="form.module_objectives.splice(index, 1)"
                        >
                            Remove
                        </button>
                    </li>
                </ol>
                <button
                    type="button"
                    class="btn mt-4 mb-8 w-max btn-sm btn-info"
                    @click="
                        form.module_objectives.push({
                            number: `${form.number}.${form.module_objectives.length + 1}`,
                            objective: '',
                        })
                    "
                >
                    Add Objective
                </button>
            </div>
            <div class="flex flex-col">
                <label class="my-4 label text-2xl">
                    <span class="label-text">Assessments:</span>
                </label>
            </div>
            <ul>
            <li
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
                    class="btn w-24 text-error-content btn-sm btn-error"
                    @click="form.course_assessments.splice(index, 1)"
                >
                    Remove
                </button>
            </li>
            </ul>

            <button
                type="button"
                class="btn my-4 text-info-content btn-sm btn-info"
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
            <div class="mt-4 mb-4">
                <label class="label text-2xl">
                    <span class="label-text">Instructional Activities:</span>
                </label>
                <div
                    v-for="(activity, index) in form.course_instructions"
                    :key="index"
                    class="mb-2"
                >
                    <input
                        v-model="form.course_instructions[index]"
                        placeholder="Activity"
                        class="input-bordered input w-full max-w-xs"
                    />
                    <button
                        type="button"
                        class="btn ml-2 btn-sm btn-error"
                        @click="form.course_instructions.splice(index, 1)"
                    >
                        Remove
                    </button>
                </div>
                <button
                    type="button"
                    class="btn mb-4 text-info-content btn-sm btn-info"
                    @click="form.course_instructions.push('')"
                >
                    Add Activity
                </button>
            </div>
            <div class="flex flex-row gap-4">
                <button type="submit" class="btn btn-success">Save</button>
                <button
                    type="button"
                    class="btn btn-error"
                    @click="emit('close')"
                >
                    Cancel
                </button>
            </div>
        </form>
        <form
            v-if="props.parent === 'storyboard'"
            @submit.prevent="form.post('/modules')"
        >
            <div class="flex flex-row gap-4">
                <p>Module:</p>
                <input
                    type="number"
                    class="input-bordered input w-full max-w-xs"
                    v-model="form.number"
                    required
                />
            </div>
            <label class="label">
                <span class="label-text">Module Name</span>
            </label>
            <input
                type="text"
                placeholdeer="Module Name"
                class="input-bordered input w-full max-w-xs"
                v-model="form.title"
                required
            />
            <label class="label">
                <span class="label-text">Aligned CourseeObjectives</span>
            </label>
            <div
                v-for="(objective, index) in courseObjectives"
                :key="index"
                class="mb-2"
            >
                <input
                    type="checkbox"
                    :value="objective.number"
                    :checked="form.course_objectives.includes(objective.number)"
                    class="checkbox mr-2 checkbox-primary"
                />
                <label>{{ objective.number }} - {{ objective.text }}</label>
            </div>
            <span class="label-text">Module Objectives</span>

            <div
                v-for="(objective, index) in form.module_objectives"
                :key="index"
                class="mb-2"
            >
                <input
                    type="text"
                    v-model="form.module_objectives[index]"
                    placeholder="Module Objective"
                    class="input-bordered input w-full max-w-xs"
                />
                <button
                    type="button"
                    class="btn ml-2 btn-sm btn-error"
                    @click="form.module_objectives.splice(index, 1)"
                >
                    Remove
                </button>
            </div>
            <button
                type="button"
                class="btn mb-4 btn-sm btn-primary"
                @click="
                    form.module_objectives.push({ number: '', objective: '' })
                "
            >
                Add Objective
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
import {  CourseModule, Course_Assessment, ModuleObjective } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ModuleObjectiveDropDown from './ModuleObjectiveDropDown.vue';

interface Props {
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
            course_objectives: [];
            course_assessments: Course_Assessment[];
            instructions: string[];
            instructional_materials: string[];
            media_library_needs: string[];
        }[];
    };
  module: CourseModule | null;
    parent: string;
    numberOfModules: number;
}

const props = defineProps<Props>();
const emit = defineEmits(['close']);

const courseObjectives = ref(props.course.objectives);
const objectives = computed(() => {
    return form.module_objectives;
});

const form = useForm({
    title: props.module?.title,
    number: props.module?.order_index,
    module_objectives: (props.module?.module_objectives ??
        []) as ModuleObjective[],
    course_objectives: props.module?.course_objectives?.map(obj => obj.number) ?? [],
    course_assessments:props.module?.assessments as Course_Assessment[],
    course_instructions: props.module?.instructions ?? [],
    course_materials: props.module?.materials[],
    course_media_library_needs: props.module?.needs[],
    course_id: props.course.id,
});

const handleSubmit = () => {
    form.put(`/course_modules/${props.module?.id}`, {
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
