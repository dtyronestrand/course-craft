<template>
  <div class="max-w-none prose mt-4 border" v-if="!isEditing">
      <div class="flex flex-row justify-between items-center text-xl p-4 w-full border-b border-secondary bg-primary ">
        <h4>Page: {{ props.item.itemable?.title }}</h4>
        <div>
                <button @click="isEditing = true" class="btn btn-sm btn-success text-success-content mt-4">Edit Page</button>
        <button @click="deletePage" class="btn btn-sm btn-error text-error-content mt-4 ml-4">Delete Page</button>
        
        </div>
      </div>
        <div class="p-4" v-html="props.item.itemable?.content"></div>
    </div>
    <div v-else class="mt-4 border" >
    <form @submit.prevent="updatePage">
        <div class="flex flex-row gap-4 items-center text-xl mb-4 p-4 w-full border-b border-secondary bg-primary">
    <h4 class="m-0">Page: </h4>
        <input v-model="localItemable.title" type="text" class="input input-bordered" />
    </div>
      
        <TipTap v-model="localItemable.content" />
     
        <div class="flex flex-row gap-4 p-4">
           <button type="submit" class="btn btn-sm btn-success text-success-content mt-4">Update Page</button>
           <button @click="isEditing = false" class="btn btn-sm btn-error text-error-content mt-4 ml-4">Cancel</button>
        </div>
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