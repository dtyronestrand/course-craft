<template>
  <div>
    <label class="label">Question</label>
    <input type="text" class="input input-bordered w-full" v-model="localQuestion.stem" />
    <label class="label">Options</label>
    <div v-for="(option, index) in localQuestion.options" :key="index" class="flex items-center mb-2">
      <input type="radio" :name="'correct_answer_' + questionIndex" :value="option" v-model="localQuestion.correct_answer" class="radio radio-primary">
      <input type="text" class="input input-bordered w-full ml-2" v-model="localQuestion.options[index]" />
      <button type="button" class="btn btn-sm btn-error ml-2" @click="removeOption(index)">Remove</button>
    </div>
    <button type="button" class="btn btn-sm btn-info" @click="addOption">Add Option</button>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Question {
  stem: string;
  type: string | null;
  options: string[];
  correct_answer: string;
}

const props = defineProps<{
  question: Question;
  questionIndex: number;
}>();

const emit = defineEmits<{
  'update:question': [question: Question]
}>();

const localQuestion = computed({
  get: () => props.question,
  set: (value) => emit('update:question', value)
});

const addOption = () => {
  emit('update:question', { ...props.question, options: [...props.question.options, ''] });
};

const removeOption = (index: number) => {
  const newOptions = [...props.question.options];
  newOptions.splice(index, 1);
  emit('update:question', { ...props.question, options: newOptions });
};
</script>