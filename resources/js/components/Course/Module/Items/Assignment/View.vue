<template>
    <div class="max-w-none prose mt-4 border" v-if="!isEditing">
         <div class="flex flex-row justify-between items-center text-xl p-4 w-full border-b border-secondary bg-primary ">
        <h4 >Assignment: {{ module.items[index].itemable?.title}} </h4>
        <div>
                <button class="btn-sm btn btn-success mt-4" @click="isEditing=true">Edit Assignment</button>
        <button class="btn btn-sm ml-4 btn-error mt-4" @click="deleteAssignment">Delete Assignment</button>
        </div>
         </div>
           <div class="max-w-none prose">
        <h5 class="text-lg w-full p-4 bg-neutral border-b border-t ">Purpose</h5>
        <div class="p-4" v-html="module.items[index].itemable?.purpose"></div>
           </div>
        <h5 class="text-lg w-full p-4 bg-neutral border-b border-t">Criteria</h5>
        <div class="p-4" v-html="module.items[index].itemable?.criteria">    
        </div>
        <h5 class="text-lg w-full p-4 bg-neutral border-b border-t">Settings</h5>
        <div class="p-4">
            <p>Point Value: {{ module.items[index].itemable?.settings?.point_value }}</p
>
            <p>Submission Types: {{ (module.items[index].itemable?.settings as AssignmentSettings)?.submission_type?.join(', ') }}</p>
        </div>

    </div>
<div v-else>
    <form @submit.prevent="updateAssignment" class="border border-secondary ">
        <input v-model="form.title" type="text" class="input  h-full p-4 bg-base-300 w-full max-w-md text-3xl mb-4 mt-2" />
        <h4 class="p-4 text-2xl">Purpose</h4>
        <TipTap v-model="form.purpose" />
        <h4 class="p-4 text-2xl">Criteria</h4>
        <TipTap v-model="form.criteria" />
        <h4 class="p-4 text-2xl">Settings</h4>
        <div class="p-4">
            <input v-model="form.settings.point_value" type="number" class="input input-bordered w-full mx-4 max-w-xs mb-4 mt-2" placeholder="Point Value" />
            <label for="submission_type" class="flex items-center space-x-2 mb-4 mx-4">Submision Type:     </label>
            <select multiple v-model="form.settings.submission_type" id="submission_type" class="select select-bordered mx-4 w-full max-w-xs">
                <option value="file_upload">File Upload</option>
                <option value="text_entry">Text Entry</option>
                <option value="url_submission">URL Submission</option>
            </select>
        </div>
           <button type="submit" class="btn btn-md btn-success text-success-content mt-4">Update Assignment</button>
           <button type="button" class="btn btn-md btn-error text-error-content mt-4" @click="isEditing=false">Cancel</button>
    </form>
    </div>
</template>

<script setup lang="ts">
import { CourseModule, AssignmentSettings } from "@/types";
import { ref } from "vue";
import {useForm} from "@inertiajs/vue3";
import TipTap from "@/components/TipTap.vue";

const props = defineProps<{
    module: CourseModule;
    index: number;
}>();

const isEditing = ref(false);


const form = useForm({
    title: props.module.items[props.index].itemable?.title || '',
    purpose: props.module.items[props.index].itemable?.purpose || '',
    criteria: props.module.items[props.index].itemable?.criteria || '',
    settings: (props.module.items[props.index].itemable?.settings as AssignmentSettings) || { point_value: 0, submission_type: [] },
});

const updateAssignment = () => {
    form.put(`/course_assignments/${props.module.items[props.index].itemable_id}`, {
        onSuccess: () => {
            isEditing.value = false;
        }
    });
};

const deleteAssignment = () => {
    form.delete(`/course_assignments/${props.module.items[props.index].itemable_id}`);
};
</script>

<style scoped>

</style>