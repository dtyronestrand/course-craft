<template>
    <div v-if="!isEditing">
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Page: {{ props.item.itemable?.title }}</h3>
        <div class="p-4" v-html="props.item.itemable?.content"></div>
        <button @click="isEditing = true" class="btn btn-md btn-success text-success-content mt-4">Edit Page</button>
        <button @click="deletePage" class="btn btn-md btn-error text-error-content mt-4 ml-4">Delete Page</button>
    </div>
    <div v-else>
    <form @submit.prevent="updatePage">
    <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Page: </h3>
        <input v-model="localItemable.title" type="text" class="input input-bordered w-full max-w-xs mb-4 mt-2" />
        <TipTap v-model="localItemable.content" />
           <button type="submit" class="btn btn-md btn-success text-success-content mt-4">Update Page</button>
           <button @click="isEditing = false" class="btn btn-md btn-error text-error-content mt-4 ml-4">Cancel</button>
    </form>
    </div>
</template>

<script setup lang="ts">
import TipTap from '@/components/TipTap.vue';
import { CourseModule } from '@/types';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

interface Props {
    module: CourseModule;
    item: any;
    index: number;
}
const props = defineProps<Props>();

const isEditing = ref(false);

// Create a local reactive copy for editing
const localItemable = ref({
    title: props.item.itemable.title,
    content: props.item.itemable.content,
});

const form = useForm({
    title: localItemable.value.title,
    content: localItemable.value.content,
});

const updatePage = () => {
    form.title = localItemable.value.title;
    form.content = localItemable.value.content;
    form.put(`/course_pages/${props.item.itemable_id}`, {
        onSuccess: () => {
            isEditing.value = false;
        }
    });

};
const deletePage = () => {
    form.delete(`/course_pages/${props.item.itemable_id}`);
};


</script>


<style scoped>

</style>