<template>
    <div>
        <label class="label">Question</label>
        <input
            type="text"
            class="input-bordered input w-full"
            v-model="localQuestion.question"
        />
        <label class="label">Correct Answer</label>
        <div class="flex">
            <div class="mr-4 flex items-center">
                <input
                    type="radio"
                    :name="'correct_answer_' + questionIndex"
                    :value="true"
                    v-model="localQuestion.correct_answer"
                    class="radio radio-primary"
                />
                <span class="ml-2">True</span>
            </div>
            <div class="flex items-center">
                <input
                    type="radio"
                    :name="'correct_answer_' + questionIndex"
                    :value="false"
                    v-model="localQuestion.correct_answer"
                    class="radio radio-primary"
                />
                <span class="ml-2">False</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Question {
    question: string;
    type: string | null;
    options: string[];
    correct_answer: string | boolean;
}

const props = defineProps<{
    question: Question;
    questionIndex: number;
}>();

const emit = defineEmits<{
    'update:question': [question: Question];
}>();

const localQuestion = computed({
    get: () => props.question,
    set: (value) => emit('update:question', value),
});
</script>
