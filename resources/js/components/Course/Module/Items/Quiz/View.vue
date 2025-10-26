<template>
 <div v-if="!isEditing">
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Quiz: {{ module.items[index].itemable?.title}} </h3>
        {{ module.items[index].itemable }}
        <h4 class="mt-4">Instructions</h4>
        <div class="p-4" v-html="module.items[index].itemable?.instructions"></div>
      
        <h4 class="prose">Settings</h4>
        <div class="p-4"> 
            <p>Time Limit: {{ module.items[index].itemable?.settings?.time_limit }}</p>
            <p>Number of Attempts: {{ module.items[index].itemable?.settings?.attempts }}</p>
            <p>Point Value: {{ module.items[index].itemable?.settings?.point_value }}</p>
        </div>
        <h4 class="prose">Questions</h4>
  
            <ol v-for="(question, qIndex) in module.items[index].itemable?.questions" :key="qIndex" class="list-none list-inside">
                <li>
                    <component :is="getQuestionComponent(question.type)" :question="question" :editing="false" :question-index="qIndex" />
                </li>
            </ol>
   
        <button class="btn btn-success mt-4" @click="isEditing=true">Edit Quiz</button>
        <button class="btn ml-4 btn-error mt-4" @click="deleteQuiz">Delete Quiz</button>
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
        <h4 class="prose">Settings</h4>
        <div class="p-4"> 
            <p>Time Limit: <input type="number" v-model="form.settings.time_limit" /></p>
            <p>Number of Attempts: <input type="number" v-model="form.settings.number_of_attempts" /></p>
            <p>Point Value: <input type="number" v-model="form.settings.point_value" /> </p>
        </div>
        <button class="btn btn-success mt-4" @click="updateQuiz">Save</button>
        <button class="btn ml-4 btn-error mt-4" @click="cancel">Cancel</button>
    </div>
</template>

<script setup lang="ts">
import { CourseModule} from "@/types";
import { defineAsyncComponent, ref } from "vue";
import {useForm} from "@inertiajs/vue3";
import TipTap from "@/components/TipTap.vue";


const props = defineProps<{
    module: CourseModule;
    index: number;
}>();

const isEditing = ref(false);

const typeMap: Record<string, any> = {
    'multiple_choice': defineAsyncComponent(() => import('./QuestionTypes/MultipleChoice/View.vue')),

};

const getQuestionComponent = (type: string) => typeMap[type] || null;

const form = useForm({
    title: props.module.items[props.index].itemable?.title || '',
    instructions: props.module.items[props.index].itemable?.instructions || '',
    questions: props.module.items[props.index].itemable?.questions as any || [],
    settings: props.module.items[props.index].itemable?.settings as any,
});

const updateQuiz = () => {
    form.put(`/course_assignments/${props.module.items[props.index].itemable_id}`, {
        onSuccess: () => {
            isEditing.value = false;
        }
    });
};
const cancel = () =>{
    isEditing.value = false;
}
const deleteQuiz = () => {
    form.delete(`/course_assignments/${props.module.items[props.index].itemable_id}`);
};
</script>

<style scoped>

</style>