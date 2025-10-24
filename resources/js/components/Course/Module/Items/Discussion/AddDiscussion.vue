<template>
    <form @submit.prevent="discussionSubmit" class=" flex flex-col border border-secondary ">
        <div class="text-3xl p-4 border-b border-secondary bg-primary">
        <input v-model="discussionData.title" type="text" class="input  h-full p-4 bg-base-300 w-full max-w-md text-3xl mb-4 mt-2" />
        </div>
        <h4 class="p-4 text-2xl">Prompt</h4>
        <TipTap v-model="discussionData.prompt" />
     
        <h4 class="p-4 text-2xl">Settings</h4>
  <div class="flex flex-row">
        <label for="graded" class="flex items-center p-4 mb-4 mx-4">Graded   </label>
<input name="graded" type="checkbox" v-model="discussionData.settings.graded" id="graded" class="checkbox checkbox-bordered mx-4" />
  </div>
  <div class="flex flex-row" v-if="discussionData.settings.graded" >
<label for="point_value" class="p-4  mx-4">Point Value</label>
   <input v-model="discussionData.settings.point_value" type="number" class="input input-bordered w-full mx-4 max-w-xs mb-4 mt-2" placeholder="Point Value" />
    </div>

            <div class="flex flex-row ">
           <button type="submit" class="btn btn-md btn-success text-success-content m-4 w-50 max-w-xs ">Save Item</button>
           <button @click="handleClose" class="btn btn-md btn-error text-error-content m-4 w-50 max-w-xs ">Cancel</button>
            </div>
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
const discussionData = ref({
    title: 'Discussion Title',
    prompt: '',
    settings: {
        graded: false,
        point_value: 0,
        
    },
})
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
        }
    });
};
</script>

<style scoped>

</style>