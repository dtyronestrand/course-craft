<template>
    <div class="max-w-none prose mt-4 border" v-if="!isEditing">
    <details>
      <summary class="flex flex-row justify-between items-center text-xl p-4 w-full border-b border-secondary bg-primary ">
        <h4 class="text-primary-content">Discussion: {{ module.items[index].itemable?.title}} </h4>
     <div class="flex flex-row gap-4">
              <EditButton background="success" @click="isEditing = true" />
       <DeleteButton @click="deleteDiscussion" />
    </div>
      </summary>
      <div class="prose max-w-none">
        <h5 class=" text-lg w-full p-4 bg-neutral border-b border-t">Prompt</h5>
        <div class="p-4" v-html="module.items[index].itemable?.prompt"></div>
      
        <h5 class="text-lg w-full p-4 bg-neutral border-b border-t">Settings</h5>
        <div class="p-4"> 
            <p>Graded: {{ discussionSettings?.graded ? 'Yes' : 'No' }}</p>
            <p v-if="discussionSettings?.graded">Point Value: {{ discussionSettings?.point_value }}</p>
        </div>
      </div>
     
    </details>
    </div>
<div v-else>
    <form @submit.prevent="updateDiscussion"  class=" border border-secondary">
    <input v-model="form.title" type="text" class="input  h-full p-4 bg-base-300 w-full max-w-md text-3xl mb-4 mt-2" />
        <h4 class="p-4 text-2xl">Prompt</h4>
        <TipTap v-model="form.prompt" />
      
        <h4 class="p-4 text-2xl">Settings</h4>
        <div class="p-4">
        <label for="graded">Graded</label>
<input type="checkbox" v-model="form.settings.graded" id="graded" class="checkbox checkbox-bordered mx-4" />

            <input v-if="form.settings.graded" v-model="form.settings.point_value" type="number" class="input input-bordered w-full mx-4 max-w-xs mb-4 mt-2" placeholder="Point Value" />

    </div>
    <div class="flex flex-row gap-4 p-4">
          <UpdateButton :itemType="props.module.items[index].itemable_type"/>
           <CancelButton @click="isEditing=false"/>
    </div>
    </form>
    </div>
 
</template>

<script setup lang="ts">
import { CourseModule } from "@/types";
import { ref, computed } from "vue";
import {useForm} from "@inertiajs/vue3";
import TipTap from "@/components/TipTap.vue";
import EditButton from "@/components/EditButton.vue";
import DeleteButton from "@/components/DeleteButton.vue";
import UpdateButton from "@/components/Course/Module/Items/Buttons/UpdateButton.vue";
import CancelButton from "../Buttons/CancelButton.vue";

const props = defineProps<{
    module: CourseModule;
    index: number;
}>();

const isEditing = ref(false);

// Cast settings to any for the discussion view so template won't error on union types
const discussionSettings = computed(() => {
    return props.module.items[props.index].itemable?.settings as any;
});

const form = useForm({
    title: props.module.items[props.index].itemable?.title || '',
    prompt: props.module.items[props.index].itemable?.prompt || '',
    settings: (props.module.items[props.index].itemable?.settings as any) || {
    point_value: 0, graded: false},
});

const updateDiscussion = () => {
    form.put(`/course_discussions/${props.module.items[props.index].itemable_id}`, {
        onSuccess: () => {
            isEditing.value = false;
        }
    });
};

const deleteDiscussion = () => {
    form.delete(`/course_discussions/${props.module.items[props.index].itemable_id}`);
};
</script>

<style scoped>

</style>