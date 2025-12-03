<template>
    <form @submit.prevent="wrapUpSubmit" class="my-8 border border-secondary">
        <h3 class="border-b border-secondary bg-primary p-4 text-3xl">
            Module {{ props.module.order_index }} {{ props.module.title }} Wrap
            Up
        </h3>
        <h4 class="p-4 text-2xl">Wrap Up Content</h4>
        <TipTap v-model="wrapUpData.content" />
        <div class="flex flex-row gap-4 p-4">
            <input type="hidden" name="module" :value="props.module.id" />
            <button
                type="submit"
                class="btn mt-4 text-success-content btn-md btn-success"
            >
                Save Item
            </button>
            <button
                @click.prevent="emit('close')"
                class="btn mt-4 text-error-content btn-md btn-error"
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
const wrapUpData = ref({
    content: '',
});
const form = useForm({
    content: wrapUpData.value.content,
    module: props.module.id,
});

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const wrapUpSubmit = () => {
    form.content = wrapUpData.value.content;
    form.post('/module_wrapUps', {
        onSuccess: () => {
            emit('close');
        },
    });
};
</script>

<style scoped></style>
