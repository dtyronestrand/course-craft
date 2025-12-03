<template>
    <div>
        <label class="label">Question</label>
        <TipTap v-model="localQuestion.question" />
    </div>
</template>

<script setup lang="ts">
import TipTap from '@/components/TipTap.vue';
import { computed } from 'vue';

interface Question {
    question: string;
    type: string | null;
    options?: string[];
    correct_answer?: string | boolean;
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

<style scoped></style>
