<template>
    <form
        @submit.prevent="discussionSubmit"
        class="flex flex-col border border-secondary"
    >
        <div class="border-b border-secondary bg-primary p-4 text-3xl">
            <input
                v-model="discussionData.title"
                type="text"
                class="input mt-2 mb-4 h-full w-full max-w-md bg-base-300 p-4 text-3xl"
            />
        </div>
        <h4 class="p-4 text-2xl">Prompt</h4>
        <TipTap v-model="discussionData.prompt" />

        <h4 class="p-4 text-2xl">Settings</h4>
        <div class="flex flex-row">
            <label for="graded" class="mx-4 flex items-center pl-4"
                >Graded
            </label>
            <input
                name="graded"
                type="checkbox"
                v-model="discussionData.settings.graded"
                id="graded"
                class="checkbox-bordered checkbox mx-4"
            />
        </div>
        <div class="flex flex-row" v-if="discussionData.settings.graded">
            <label for="point_value" class="mx-4 p-4">Point Value</label>
            <input
                v-model="discussionData.settings.point_value"
                type="number"
                class="input-bordered input mx-4 mt-2 mb-4 w-full max-w-xs"
                placeholder="Point Value"
            />
        </div>

        <div class="flex flex-row">
            <button
                type="submit"
                class="btn m-4 w-50 max-w-xs text-success-content btn-sm btn-success"
            >
                Save Item
            </button>
            <button
                @click="handleClose"
                class="btn m-4 w-50 max-w-xs text-error-content btn-sm btn-error"
            >
                Cancel
            </button>
        </div>
    </form>
</template>

<script setup lang="ts">
import TipTap from '@/components/TipTap.vue';
import { CourseModule } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
interface Props {
    module: CourseModule;
}
const props = defineProps<Props>();
const discussionData = ref({
    title: 'Discussion Title',
    prompt: '',
    settings: {
        graded: false,
        point_value: 0,
    },
});
const form = useForm({
    title: discussionData.value.title,
    prompt: discussionData.value.prompt,
    settings: discussionData.value.settings,
    module: props.module.id,
});

const emit = defineEmits<{
    (e: 'close'): void;
}>();
const handleClose = () => {
    emit('close');
};
const discussionSubmit = () => {
    form.title = discussionData.value.title;
    form.prompt = discussionData.value.prompt;
    form.settings = discussionData.value.settings;
    form.post('/course_discussions', {
        onSuccess: () => {
            emit('close');
        },
    });
};
</script>

<style scoped></style>
