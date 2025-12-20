<template>
    <div class="bg-base-300/50 w-full h-full flex items-center justify-center fixed inset-0 z-50" @click.self="emit('modal-close')">
        <div v-if="props.isOpen" class="bg-base-100 rounded-lg shadow-md shadow-primary p-4 max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold mb-4">{{props.course.prefix}} {{ props.course.number }}</h1>
        </div>
        </div>
</template>

<script setup lang="ts">
import type { Course } from '@/types';

interface Props {
    isOpen: boolean;
    course: Course;
}
const props = defineProps<Props>();
const emit = defineEmits(['modal-close']);

</script>

<style scoped>

</style>