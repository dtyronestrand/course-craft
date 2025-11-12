<template>
    <div v-if="!editing">
        <h5>True/False</h5>
        <p class="ml-4">{{ question.question }}</p>
        <p class="ml-4 font-bold">Correct Answer: {{ question.correct_answer ? 'True' : 'False' }}</p>
    </div>
    <div v-else>
        <label class="label">Question</label>
    <input type="text" class="input input-bordered w-full" v-model="question.question" />
    <label class="label">Correct Answer</label>
    <div class="flex">
        <div class="flex items-center mr-4">
            <input type="radio" :value="true" v-model="question.correct_answer" class="radio radio-primary">
            <span class="ml-2">True</span>
        </div>
        <div class="flex items-center">
            <input type="radio"  :value="false" v-model="question.correct_answer" class="radio radio-primary">
            <span class="ml-2">False</span>
        </div>
    </div>
    </div>
</template>

<script setup lang="ts">
import {ref} from "vue";

interface Props {
    question: {
        question: string;
        type: string | null;
        options?: string[];
        correct_answer?: boolean;
    }
    editing: boolean;
    
}

const props = defineProps<Props>();

const question = ref(props.question);
</script>

<style scoped>

</style>