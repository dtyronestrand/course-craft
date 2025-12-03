<template>
    <div class="mb-8 flex flex-col gap-8 border border-accent bg-base-300 p-4">
        <h2 class="mb-8 pl-4 text-5xl font-bold">Course Map</h2>
        <div v-if="props.course.modules?.length">
            <table
                class="nowrap mx-auto mt-16 w-full border-collapse border-2 border-accent"
            >
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Aligned Course Objectives</th>
                        <th>Module Objectives</th>
                        <th>Assessments</th>
                        <th>Instructional Activities</th>
                        <th>Instructional Materials</th>
                        <th>Media/Library Needs</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class=""
                        v-for="module in props.course.modules"
                        :key="module.id"
                    >
                        <td class="sticky">
                            {{ module.order_index }}: &nbsp; {{ module.title }}
                        </td>
                        <td>
                            <div
                                v-for="objective in module.course_objectives"
                                :key="objective.id"
                            >
                                {{ objective.number }}
                            </div>
                        </td>
                        <td>
                            <div
                                v-for="(
                                    objective, index
                                ) in module.module_objectives"
                                :key="index"
                            >
                                {{ objective.number }}:
                                {{ objective.objective }}
                            </div>
                        </td>
                        <td>
                            <ul
                                v-for="(
                                    assessment, index
                                ) in module.assessments"
                                :key="index"
                            >
                                <li>{{ assessment.title }}</li>
                                <li
                                    v-for="objective in assessment.objectives"
                                    :key="objective.id"
                                >
                                    {{ module.order_index }}.{{
                                        objective.number
                                    }}
                                </li>
                            </ul>
                        </td>
                        <td>
                            <ul
                                v-for="(
                                    instruction, index
                                ) in module.instructions"
                                :key="index"
                            >
                                <li>{{ instruction.title }}</li>
                                <li
                                    v-for="objective in instruction.objectives"
                                    :key="objective.id"
                                >
                                    {{ module.order_index }}.{{
                                        objective.number
                                    }}
                                </li>
                            </ul>
                        </td>

                        <td>
                            <ul
                                v-for="(material, index) in module.materials"
                                :key="index"
                            >
                                <li>{{ material.title }}</li>
                                <li
                                    v-for="objective in material.objectives"
                                    :key="objective.id"
                                >
                                    {{ module.order_index }}.{{
                                        objective.number
                                    }}
                                </li>
                            </ul>
                        </td>
                        <td>
                            <ul
                                v-for="(media, index) in module.media_libraries"
                                :key="index"
                            >
                                <li>{{ media.title }}</li>
                                <li
                                    v-for="objective in media.objectives"
                                    :key="objective.id"
                                >
                                    {{ module.order_index }}.{{
                                        objective.number
                                    }}
                                </li>
                            </ul>
                        </td>
                        <td class="flex flex-col">
                            <EditButton
                                @click="editModule(module)"
                                background="info"
                            />
                        </td>
                        <td class="flex flex-col">
                            <DeleteButton @click="deleteModule(module)" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <table
                class="nowrap mx-auto mt-16 w-full border-collapse border-2 border-accent"
            >
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Aligned Course Objectives</th>
                        <th>Module Objectives</th>
                        <th>Assessments</th>
                        <th>Instructional Activities</th>
                        <th>Instructional Materials</th>
                        <th>Media/Library Needs</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class=""
                        v-for="module in props.course.modules"
                        :key="module.id"
                    >
                        <td class="sticky">
                            {{ module.order_index }}: &nbsp;{{
                                module.course_materials
                            }}
                        </td>
                        <td>{{ module.course_media_library_needs }}</td>
                        <td class="flex flex-col">
                            <EditButton
                                @click="editModule(module)"
                                background="info"
                            />
                        </td>
                        <td class="flex flex-col">
                            <DeleteButton @click="deleteModule(module)" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <button
                :style="{ display: addModuleModalOpen ? 'none' : '' }"
                class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block translate-y-[-2px] cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-info px-[1.5em] py-[0.25em] text-center font-bold text-info-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                @click="addModule"
            >
                Add Module
            </button>
        </div>
        <AddModule
            v-if="addModuleModalOpen"
            :numberOfModules="props.numberOfModules"
            :course="props.course"
            :parent="'map'"
            @close="addModuleModalOpen = false"
        />
        <EditModule
            v-if="isEditing"
            :module="moduleToEdit"
            :course="props.course"
            :parent="'map'"
            :numberOfModules="props.numberOfModules"
            @close="() => (isEditing = false)"
        />
    </div>
</template>

<script setup lang="ts">
import AddModule from '@/components/Course/Module/AddModule.vue';
import EditModule from '@/components/Course/Module/EditModule.vue';
import DeleteButton from '@/components/DeleteButton.vue';
import EditButton from '@/components/EditButton.vue';
import { Course, CourseModule } from '@/types';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
interface Props {
    course: Course;
    numberOfModules: number;
}
const props = defineProps<Props>();
const addModuleModalOpen = ref(false);
const addModule = () => {
    addModuleModalOpen.value = true;
};

const isEditing = ref(false);
const moduleToEdit = ref<CourseModule | null>(null);
const editModule = (module: CourseModule) => {
    isEditing.value = true;
    moduleToEdit.value = module;
};
const deleteModule = (module: CourseModule) => {
    if (confirm('Are you sure you want to delete this module?')) {
        router.delete(`/course_modules/${module.id}`);
    }
};
</script>

<style scoped>
th,
td {
    padding: 12px 16px;
    text-align: left;
}

th {
    text-align: center;
}

thead {
    background: var(--color-primary);
    font-weight: bold;
    color: var(--color-primary-content);
}

tbody tr:nth-child(even) {
    background: var(--color-base-200);
}
tbody tr:nth-child(odd) {
    background: var(--color-base-100);
}
td {
    word-break: break-word;
}

tr td:first-child {
    word-break: keep-all;
}
</style>
