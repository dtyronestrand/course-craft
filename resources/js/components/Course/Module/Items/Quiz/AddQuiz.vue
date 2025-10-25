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
        <button type="button" class="btn btn-sm btn-primary m-4" @click="quizData.questions.push({ question: '', type: null, options: [], correct_answer: '' })">Add Question</button>

        </div>
           <button type="submit" class="btn btn-md btn-success text-success-content mt-4">Save Item</button>

    </form>
</template>

<script setup lang="ts">
import {ref} from "vue";

const quizData = ref({
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
})

const quizSubmit = () => {
    console.log(quizData.value)
}
</script>

<style scoped>

</style>