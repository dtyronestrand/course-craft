<template>
    <form @submit.prevent="pageSubmit" class="border border-secondary">
        <input
            v-model="pageData.title"
            type="text"
            class="input-bordered input mt-2 mb-4 w-full max-w-md p-4 text-3xl"
        />
        <h4 class="p-4 text-2xl">Page Content</h4>
        <TipTap v-model="pageData.content" />
        <div class="flex flex-row gap-4 p-4">
            <button
                type="submit"
                class="btn mt-4 text-success-content btn-sm btn-success"
            >
                Save Item
            </button>
            <button
                @click.prevent="emit('close')"
                class="btn mt-4 text-error-content btn-sm btn-error"
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
const pageData = ref({
    title: 'Page Title',
    content: '',
});
const form = useForm({
    title: pageData.value.title,
    content: pageData.value.content,
    module: props.module.id,
});

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const pageSubmit = () => {
    form.content = pageData.value.content;
    form.title = pageData.value.title;
    form.post('/course_pages', {
        onSuccess: () => {
            emit('close');
        },
    });
};
</script>

<style scoped></style>
