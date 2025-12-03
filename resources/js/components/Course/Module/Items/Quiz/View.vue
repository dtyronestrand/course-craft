<template>
    <div class="prose mt-4 max-w-none border" v-if="!isEditing">
        <details>
            <summary
                class="flex w-full flex-row items-center justify-between border-b border-secondary bg-primary p-4 text-xl"
            >
                <h4 class="text-primary-content">
                    Quiz: {{ module.items[index].itemable?.title }}
                </h4>
                <div class="flex flex-row gap-4">
                    <EditButton
                        background="success"
                        @click="isEditing = true"
                    />
                    <DeleteButton @click="deleteQuiz" />
                </div>
            </summary>

            <h5 class="w-full border-t border-b bg-neutral p-4 text-lg">
                Instructions
            </h5>
            <div
                class="p-4"
                v-html="module.items[index].itemable?.instructions"
            ></div>

            <h5 class="w-full border-t border-b bg-neutral p-4 text-lg">
                Settings
            </h5>
            <div class="p-4">
                <p>
                    Time Limit:
                    {{ module.items[index].itemable?.settings?.time_limit }}
                </p>
                <p>
                    Number of Attempts:
                    {{ module.items[index].itemable?.settings?.attempts }}
                </p>
                <p>
                    Point Value:
                    {{ module.items[index].itemable?.settings?.point_value }}
                </p>
            </div>
            <details>
                <summary>
                    <h4 class="w-full border-t border-b bg-neutral p-4 text-lg">
                        Questions
                    </h4>
                </summary>

                <ol
                    v-for="(question, qIndex) in module.items[index].itemable
                        ?.questions"
                    :key="qIndex"
                    class="mr-12 flex w-full list-inside list-none flex-col items-center"
                >
                    <li class="mr-4 h-full w-full flex-1">
                        <h5
                            class="max-w-none bg-info p-4 text-lg text-info-content"
                        >
                            Question {{ qIndex + 1 }}
                        </h5>
                        <component
                            class="border border-secondary p-4"
                            :is="getQuestionComponent(question.type)"
                            :question="question"
                            :editing="false"
                            :question-index="qIndex"
                        />
                    </li>
                </ol>
            </details>
        </details>
    </div>
    <div v-else>
        <h3 class="border-b border-secondary bg-primary p-4 text-3xl">Quiz:</h3>
        <input
            v-model="form.title"
            type="text"
            class="input mt-2 mb-4 h-full w-full max-w-md bg-base-300 p-4 text-3xl"
        />
        <h4 class="mt-4">Instructions</h4>
        <tip-tap v-model="form.instructions" />
        <h4 class="prose">Questions</h4>
        <ol
            v-for="(question, qIndex) in form.questions"
            :key="qIndex"
            class="list-inside list-none"
        >
            <li>
                <component
                    :is="getQuestionComponent(question.type)"
                    :question="question"
                    :editing="true"
                    :question-index="qIndex"
                />
            </li>
        </ol>
        <div class="mt-4 rounded border border-secondary p-4">
            <label class="label">Add New Question</label>
            <select
                v-model="newQuestion.type"
                class="select-bordered select w-full max-w-xs"
            >
                <option :value="null" disabled>Select Question Type</option>
                <option
                    v-for="(type, key) in questionTypes"
                    :key="key"
                    :value="key"
                >
                    {{ type.name }}
                </option>
            </select>
            <component
                v-if="newQuestion.type"
                :is="questionTypes[newQuestion.type].component"
                :question="newQuestion"
                :editing="true"
                :question-index="-1"
            />
            <button
                type="button"
                class="btn mt-4 btn-sm btn-primary"
                @click="addQuestion"
                :disabled="!newQuestion.type"
            >
                Add Question
            </button>
        </div>
        <h4 class="prose">Settings</h4>
        <div class="p-4">
            <p>
                Time Limit:
                <input type="number" v-model="form.settings.time_limit" />
            </p>
            <p>
                Number of Attempts:
                <input
                    type="number"
                    v-model="form.settings.number_of_attempts"
                />
            </p>
            <p>
                Point Value:
                <input type="number" v-model="form.settings.point_value" />
            </p>
        </div>
        <div class="flex flex-row gap-4 p-4">
            <UpdateButton
                @click="updateQuiz"
                :itemType="props.module.items[index].itemable_type"
            />
            <CancelButton @click="isEditing = false" />
        </div>
    </div>
</template>

<script setup lang="ts">
import UpdateButton from '@/components/Course/Module/Items/Buttons/UpdateButton.vue';
import DeleteButton from '@/components/DeleteButton.vue';
import EditButton from '@/components/EditButton.vue';
import TipTap from '@/components/TipTap.vue';
import { CourseModule } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { defineAsyncComponent, ref } from 'vue';
import CancelButton from '../Buttons/CancelButton.vue';
interface Question {
    question: string;
    type: QuestionTypeKey | null;
    options?: string[];
    correct_answer?: string;
}
type QuestionTypeKey = 'multiple_choice' | 'true_false' | 'essay';
const props = defineProps<{
    module: CourseModule;
    index: number;
}>();

const isEditing = ref(false);
const newQuestion = ref<Question>({
    question: '',
    type: null,
    options: [],
    correct_answer: '',
});

const typeMap: Record<string, any> = {
    multiple_choice: defineAsyncComponent(
        () => import('./QuestionTypes/MultipleChoice/View.vue'),
    ),
    essay: defineAsyncComponent(() => import('./QuestionTypes/Essay/View.vue')),
    true_false: defineAsyncComponent(
        () => import('./QuestionTypes/TrueFalse/View.vue'),
    ),
};
const questionTypes: Record<QuestionTypeKey, { name: string; component: any }> =
    {
        multiple_choice: {
            name: 'Multiple Choice',
            component: defineAsyncComponent(
                () => import('./QuestionTypes/MultipleChoice/Create.vue'),
            ),
        },
        true_false: {
            name: 'True/False',
            component: defineAsyncComponent(
                () => import('./QuestionTypes/TrueFalse/Create.vue'),
            ),
        },
        essay: {
            name: 'Essay',
            component: defineAsyncComponent(
                () => import('./QuestionTypes/Essay/Create.vue'),
            ),
        },
        // Add other question types here
    };
const getQuestionComponent = (type: string) => typeMap[type] || null;

const form = useForm({
    title: props.module.items[props.index].itemable?.title || '',
    instructions: props.module.items[props.index].itemable?.instructions || '',
    questions:
        (props.module.items[props.index].itemable?.questions as any) || [],
    settings: props.module.items[props.index].itemable?.settings as any,
});

const updateQuiz = () => {
    form.put(`/course_quizzes/${props.module.items[props.index].itemable_id}`, {
        onSuccess: () => {
            isEditing.value = false;
        },
    });
};

const deleteQuiz = () => {
    form.delete(
        `/course_quizzes/${props.module.items[props.index].itemable_id}`,
    );
};

const addQuestion = () => {
    if (newQuestion.value.type) {
        form.questions.push({ ...newQuestion.value });
        newQuestion.value = {
            question: '',
            type: null,
            options: [],
            correct_answer: '',
        };
    }
};
</script>

<style scoped>
summary {
    list-style: none;
    cursor: pointer;
}
</style>
