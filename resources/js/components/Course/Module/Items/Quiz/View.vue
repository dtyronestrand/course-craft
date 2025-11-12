<template>
 <div class="max-w-none prose mt-4 border" v-if="!isEditing">
 <div class="flex flex-row justify-between items-center text-xl p-4 w-full border-b border-secondary bg-primary ">
        <h4>Quiz: {{ module.items[index].itemable?.title}} </h4>
    <div class="flex flex-row gap-4">
              <EditButton background="success" @click="isEditing = true" />
       <DeleteButton @click="deleteQuiz" />
    </div>
        </div>

        <h5 class="text-lg w-full p-4 bg-neutral border-b border-t ">Instructions</h5>
        <div class="p-4" v-html="module.items[index].itemable?.instructions"></div>
      
        <h5 class="text-lg w-full p-4 bg-neutral border-b border-t ">Settings</h5>
        <div class="p-4"> 
            <p>Time Limit: {{ module.items[index].itemable?.settings?.time_limit }}</p>
            <p>Number of Attempts: {{ module.items[index].itemable?.settings?.attempts }}</p>
            <p>Point Value: {{ module.items[index].itemable?.settings?.point_value }}</p>
        </div>
        <h4 class="text-lg w-full p-4 bg-neutral border-b border-t ">Questions</h4>
  
            <ol v-for="(question, qIndex) in module.items[index].itemable?.questions" :key="qIndex" class="list-none flex flex-col items-center w-full mr-12 list-inside">
                <li class="mr-4 flex-1  h-full w-full">
                    <h5 class="max-w-none text-lg text-info-content p-4 bg-info">Question {{ qIndex + 1 }}</h5>
                    <component class="p-4 border border-secondary" :is="getQuestionComponent(question.type)" :question="question" :editing="false" :question-index="qIndex" />
                </li>
            </ol>
   

    </div>
    <div v-else>
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Quiz: </h3><input v-model="form.title" type="text" class="input  h-full p-4 bg-base-300 w-full max-w-md text-3xl mb-4 mt-2" />
        <h4 class="mt-4">Instructions</h4>
        <tip-tap v-model="form.instructions" />
        <h4 class="prose">Questions</h4>
        <ol v-for="(question, qIndex) in form.questions" :key="qIndex" class="list-none list-inside">
            <li>
                <component :is="getQuestionComponent(question.type)" :question="question" :editing="true" :question-index="qIndex" />
            </li>
        </ol>
        <div class="p-4 border border-secondary rounded mt-4">
            <label class="label">Add New Question</label>
            <select v-model="newQuestion.type" class="select select-bordered w-full max-w-xs">
                <option :value="null" disabled>Select Question Type</option>
                <option v-for="(type, key) in questionTypes" :key="key" :value="key">{{ type.name }}</option>
            </select>
            <component v-if="newQuestion.type" :is="questionTypes[newQuestion.type].component" :question="newQuestion" :editing="true" :question-index="-1" />
            <button type="button" class="btn btn-sm btn-primary mt-4" @click="addQuestion" :disabled="!newQuestion.type">Add Question</button>
        </div>
        <h4 class="prose">Settings</h4>
        <div class="p-4"> 
            <p>Time Limit: <input type="number" v-model="form.settings.time_limit" /></p>
            <p>Number of Attempts: <input type="number" v-model="form.settings.number_of_attempts" /></p>
            <p>Point Value: <input type="number" v-model="form.settings.point_value" /> </p>
        </div>
               <div class="flex flex-row gap-4 p-4">
      <UpdateButton @click="updateQuiz" :itemType="props.module.items[index].itemable_type"/>
           <CancelButton @click="isEditing=false"/>
               </div>
    </div>
</template>

<script setup lang="ts">
import { CourseModule} from "@/types";
import { defineAsyncComponent, ref } from "vue";
import {useForm} from "@inertiajs/vue3";
import TipTap from "@/components/TipTap.vue";
import EditButton from "@/components/EditButton.vue";
import DeleteButton from "@/components/DeleteButton.vue";
import UpdateButton from "@/components/Course/Module/Items/Buttons/UpdateButton.vue";
import CancelButton from "../Buttons/CancelButton.vue";
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
  correct_answer: ''
});

const typeMap: Record<string, any> = {
    'multiple_choice': defineAsyncComponent(() => import('./QuestionTypes/MultipleChoice/View.vue')),
    'essay': defineAsyncComponent(() => import('./QuestionTypes/Essay/View.vue')),
    'true_false': defineAsyncComponent(() => import('./QuestionTypes/TrueFalse/View.vue')),

};
const questionTypes: Record<QuestionTypeKey, { name: string; component: any }> = {
  multiple_choice: { name: 'Multiple Choice', component: defineAsyncComponent(() => import('./QuestionTypes/MultipleChoice/Create.vue')) },
  true_false: { name: 'True/False', component: defineAsyncComponent(() => import('./QuestionTypes/TrueFalse/Create.vue')) },
  essay: { name: 'Essay', component: defineAsyncComponent(() => import('./QuestionTypes/Essay/Create.vue')) },
  // Add other question types here
};
const getQuestionComponent = (type: string) => typeMap[type] || null;

const form = useForm({
    title: props.module.items[props.index].itemable?.title || '',
    instructions: props.module.items[props.index].itemable?.instructions || '',
    questions: props.module.items[props.index].itemable?.questions as any || [],
    settings: props.module.items[props.index].itemable?.settings as any,
});

const updateQuiz = () => {
    form.put(`/course_quizzes/${props.module.items[props.index].itemable_id}`, {
        onSuccess: () => {
            isEditing.value = false;
        }
    });
};

const deleteQuiz = () => {
    form.delete(`/course_quizzes/${props.module.items[props.index].itemable_id}`);
};

const addQuestion = () => {
  if (newQuestion.value.type) {
    form.questions.push({ ...newQuestion.value });
    newQuestion.value = {
      question: '',
      type: null,
      options: [],
      correct_answer: ''
    };
  }
};
</script>

<style scoped>

</style>