<template>
    <div v-if="!editing">
        <label class="label">Essay</label>
        <div class="p-4" v-html="question.question"></div>
    </div>
    <div v-else>
        <label class="label">Question Stem</label>
        <TipTap v-model="question.question" />
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import TipTap from '@/components/TipTap.vue';

interface Props {
    question: {
        question: string;
        type: string | null;
    }
    editing: boolean;
}

const props = defineProps<Props>();
const  question = ref(props.question);

</script>

<style scoped>

</style>