<template>
    <div v-if="!editing">
        <h5>Multiple Choice</h5>
        <p class="ml-4">{{ question.stem }}</p>
        <ol
            v-for="(option, index) in question.options"
            :key="index"
            class="list-inside list-none"
        >
            <li :class="{ 'font-bold': option === question.correct_answer }">
                {{ String.fromCharCode(65 + index) }}. {{ option }}
            </li>
        </ol>
    </div>
    <div v-else>
        <label class="label">Question Stem</label>
        <input
            type="text"
            class="input-bordered input w-full"
            v-model="question.stem"
        />
        <label class="label">Options</label>
        <div v-if="question.options">
            <span
                v-for="(option, index) in question.options"
                :key="index"
                class="mb-2 flex items-center"
            >
                <input
                    type="radio"
                    :name="'correct_answer_' + index"
                    :value="option"
                    v-model="question.correct_answer"
                    class="radio radio-primary"
                />
                <input
                    type="text"
                    class="input-bordered input ml-2 w-full"
                    v-model="question.options[index]"
                />
                <button
                    type="button"
                    class="btn ml-2 btn-sm btn-error"
                    @click="question.options.splice(index, 1)"
                >
                    Remove
                </button>
            </span>
            <button
                type="button"
                class="btn btn-sm btn-primary"
                @click="question.options.push('')"
            >
                Add Option
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    question: {
        stem: string;
        type: string | null;
        options?: string[];
        correct_answer?: string | boolean;
    };
    editing: boolean;
}
const props = defineProps<Props>();

const question = ref(props.question);
</script>

<style scoped></style>
