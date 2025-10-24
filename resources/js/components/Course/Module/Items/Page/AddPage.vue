<template>
    <form @submit.prevent="pageSubmit" class="border border-secondary ">
       <input v-model="pageData.title" type="text" class="input input-bordered w-full max-w-md text-3xl p-4 mb-4 mt-2" />
        <h4 class="p-4 text-2xl">Page Content</h4>
        <TipTap v-model="pageData.content" />
           <button type="submit" class="btn btn-md btn-success text-success-content mt-4">Save Item</button>
    </form>
</template>

<script setup lang="ts">
import { CourseModule } from '@/types';
import {useForm} from '@inertiajs/vue3';
import { ref } from 'vue';
import TipTap from '@/components/TipTap.vue';
interface Props {
    module: CourseModule;
}
const props = defineProps<Props>();
const pageData = ref({
    title: 'Page Title',
    content: '',
})
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
        }
    });
};
</script>

<style scoped>

</style>