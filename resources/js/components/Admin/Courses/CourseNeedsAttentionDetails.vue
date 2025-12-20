<template>
    <div
        class="fixed inset-0 z-50 flex h-full w-full items-center justify-center bg-base-300/50"
        @click.self="emit('modal-close')"
    >
        <div
            v-if="props.isOpen"
            class="mx-auto max-w-3xl rounded-lg bg-base-100 p-4 shadow-md shadow-primary"
        >
            <h1 class="mb-4 text-2xl font-bold">
                {{ props.course.prefix }} {{ props.course.number }}
            </h1>
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

<style scoped></style>
