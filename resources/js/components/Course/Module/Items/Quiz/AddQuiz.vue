<template>
    <form @submit.prevent="quizSubmit" class="border border-secondary ">
       <input v-model="quizData.title" type="text" class="input input-bordered w-full max-w-md text-3xl p-4 mb-4 mt-2" placeholder="Quiz Title" />
        <h4 class="p-4 text-2xl">Instructions</h4>
        <TipTap v-model="quizData.instructions" />
        <h4 class="p-4 text-2xl">Settings</h4>
        <label for="time_limit" class="p-4  mx-4">Time Limit (minutes)</label>
        <input v-model="quizData.settings.time_limit" type="number" class="input input-bordered w-full mx-4 max-w-xs mb-4 mt-2" name="time_limit" placeholder="Time Limit (minutes)" />
        <label for="attempts" class="p-4  mx-4">Attempts</label>
        <input v-model="quizData.settings.attempts" type="number" class="input input-bordered w-full mx-4 max-w-xs mb-4 mt-2" name="attempts" placeholder="Attempts" />
        <label for="point_value" class="p-4  mx-4">Point Value</label>
        <input v-model="quizData.settings.point_value" type="number" class="input input-bordered w-full mx-4 max-w-xs mb-4 mt-2" name="point_value" placeholder="Point Value" />
        <div>
        <h4 class="p-4 text-2xl">Questions</h4>
        <div v-for="(question, index) in quizData.questions" :key="index" class="p-4 border-t border-secondary">
             <div class="flex justify-end">
                <button type="button" class="btn btn-sm btn-error" @click="quizData.questions.splice(index, 1)">Remove Question</button>
            </div>
            <label class="label">Question Type</label>
            <select v-model="question.type" class="select select-bordered w-full max-w-xs">
                <option :value="null" disabled>Select Question Type</option>
                <option v-for="(type, key) in questionTypes" :key="key" :value="key">{{ type.name }}</option>
            </select>
            <div v-if="question.type" class="mt-4">
                <component :is="questionTypes[question.type].component" :question="question" :question-index="index" @update:question="(newQuestion: Question) => quizData.questions[index] = newQuestion" />
            </div>
        </div>
       <button type="button" class="btn btn-sm btn-primary m-4" @click="addQuestion">Add Question</button>

        </div>
           <button type="submit" class="btn btn-md btn-success text-success-content mt-4">Save Item</button>

    </form>
</template>

<script setup lang="ts">
import { ref, defineAsyncComponent } from "vue";
import {useForm} from "@inertiajs/vue3";
import { CourseModule } from "@/types";
import TipTap from "@/components/TipTap.vue";

type QuestionTypeKey = 'multiple_choice' | 'true_false' | 'essay';

const props = defineProps<{
    module: CourseModule;
}>();

interface Question {
  question: string;
  type: QuestionTypeKey | null;
  options?: string[];
  correct_answer?: string;
}
const emit = defineEmits<{
    (e: 'close'): void;

}>();
const quizData = ref<{
  title: string;
  instructions: string;
  settings: { time_limit: number; attempts: number; point_value: number };
  questions: Question[];
}>({
  title: '',
  instructions: '',
  settings: {
    time_limit: 0,
    attempts: 0,
    point_value: 0
  },
  questions: [{
    question: '',
    type: null,
    options: [],
    correct_answer: ''
  }]
});

const questionTypes: Record<QuestionTypeKey, { name: string; component: any }> = {
  multiple_choice: { name: 'Multiple Choice', component: defineAsyncComponent(() => import('./QuestionTypes/MultipleChoice/Create.vue')) },
  true_false: { name: 'True/False', component: defineAsyncComponent(() => import('./QuestionTypes/TrueFalse.vue')) },
  essay: { name: 'Essay', component: defineAsyncComponent(() => import('./QuestionTypes/Essay.vue')) },
  // Add other question types here
};

const addQuestion = () => {
  quizData.value.questions.push({
    question: '',
    type: null,
    options: [],
    correct_answer: ''
  });
};

    const form = useForm({
        title: quizData.value.title,
        instructions: quizData.value.instructions,
        settings: quizData.value.settings,
        questions: quizData.value.questions as any,
        module: props.module.id,
    });

const quizSubmit = () => {
    form.title = quizData.value.title;
    form.instructions = quizData.value.instructions;
    form.settings = quizData.value.settings;
    form.questions = quizData.value.questions as any;

    form.post('/course_quizzes', {
        onSuccess: () => {
            emit('close');
        }
    });
};
</script>

<style scoped>

</style>