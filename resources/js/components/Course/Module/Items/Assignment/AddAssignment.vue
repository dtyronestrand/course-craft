<template>
    <form @submit.prevent="assignmentSubmit" class=" border border-secondary ">
        <div class="text-3xl p-4 border-b border-secondary bg-primary">
        <input v-model="assignmentData.title" type="text" class="input  h-full p-4 bg-base-300 w-full max-w-md text-3xl mb-4 mt-2" />
        </div>
        <h4 class="p-4 text-2xl">Purpose</h4>
        <TipTap v-model="assignmentData.purpose" />
        <h4 class="p-4 text-2xl">Criteria</h4>
        <TipTap v-model="assignmentData.criteria" />
        <h4 class="p-4 text-2xl">Settings</h4>
        <label for="point_value" class="flex items-center space-x-2 mb-4 mx-4">Point Value:     </label>
        <input v-model="assignmentData.settings.point_value" type="number" class="input input-bordered w-full mx-4 max-w-xs mb-4 mt-2" placeholder="Point Value" />
        <label for="submission_type" class="flex items-center space-x-2 mb-4 mx-4">Submision Type:     </label>
            <select multiple v-model="assignmentData.settings.submission_type" id="submission_type" class="select select-bordered mx-4 w-full max-w-xs">
                <option value="file_upload">File Upload</option>
                <option value="text_entry">Text Entry</option>
                <option value="url_submission">URL Submission</option>
            </select>
            <div class="flex flex-row ">
           <button type="submit" class="btn btn-sm btn-success text-success-content m-4 w-50 max-w-xs ">Save Item</button>
           <button @click="handleClose" class="btn btn-sm btn-error text-error-content m-4 w-50 max-w-xs ">Cancel</button>
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
const assignmentData = ref({
    title: 'Assignment Title',
    purpose: '',
    criteria: '',
    settings: {
        point_value: 0,
        submission_type: [],
    },
})
const form = useForm({
    title: assignmentData.value.title,
    purpose: assignmentData.value.purpose,
    criteria: assignmentData.value.criteria,
    settings: assignmentData.value.settings,
    module: props.module.id,
});

const emit = defineEmits<{
    (e: 'close'): void;

}>();
const handleClose = () => {
    emit('close');
};
const assignmentSubmit = () => {
    form.title = assignmentData.value.title;
    form.purpose = assignmentData.value.purpose;
    form.criteria = assignmentData.value.criteria;
    form.settings = assignmentData.value.settings;
    form.post('/course_assignments', {
        onSuccess: () => {
            emit('close');
        }
    });
};
</script>

<style scoped>

</style>