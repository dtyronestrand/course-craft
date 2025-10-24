<template>
    <div v-if="!isEditing">
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Discussion: {{ module.items[index].itemable?.title}} </h3>
        <h4 class="mt-4">Prompt</h4>
        <div class="p-4" v-html="module.items[index].itemable?.prompt"></div>
      
        <h4 class="prose">Settings</h4>
        <div class="p-4"> 
            <p>Graded: {{ discussionSettings?.graded ? 'Yes' : 'No' }}</p>
            <p v-if="discussionSettings?.graded">Point Value: {{ discussionSettings?.point_value }}</p>
        </div>
        <button class="btn btn-success mt-4" @click="isEditing=true">Edit discussion</button>
        <button class="btn ml-4 btn-error mt-4" @click="deleteDiscussion">Delete Discussion</button>
    </div>
<div v-else>
    <form @submit.prevent="updateDiscussion"  class=" h-full p-4 bg-base-300 w-full max-w-none text-3xl mb-4 mt-2">
    <input v-model="form.title" type="text" class="input  h-full p-4 bg-base-300 w-full max-w-md text-3xl mb-4 mt-2" />
        <h4 class="p-4 text-2xl">Prompt</h4>
        <TipTap v-model="form.prompt" />
      
        <h4 class="p-4 text-2xl">Settings</h4>
        <div class="p-4">
        <label for="graded">Graded</label>
<input type="checkbox" v-model="form.settings.graded" id="graded" class="checkbox checkbox-bordered mx-4" />

            <input v-if="form.settings.graded" v-model="form.settings.point_value" type="number" class="input input-bordered w-full mx-4 max-w-xs mb-4 mt-2" placeholder="Point Value" />

    </div>
           <button type="submit" class="btn btn-md btn-success text-success-content mt-4">Update discussion</button>
           <button type="button" class="btn btn-md btn-error text-error-content mt-4" @click="isEditing=false">Cancel</button>
    </form>
    </div>
 
</template>

<script setup lang="ts">
import { CourseModule } from "@/types";
import { ref, computed } from "vue";
import {useForm} from "@inertiajs/vue3";
import TipTap from "@/components/TipTap.vue";

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