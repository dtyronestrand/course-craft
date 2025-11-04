<template>
    <div class="my-8 text-xl">
       
            <label class="px-4" for="itemType">Select Item Type:</label>
            <select id="itemType" class="border border-accent" v-model="selectedItemType">
                <option value="" disabled>Select an item type</option>
                <option value="overview">Overview</option>
                <option value="page">Page</option>
                <option value="quiz">Quiz</option>
                <option value="assignment">Assignment</option>
                <option value="discussion">Discussion</option>
                <option value="wrap_up">Wrap Up</option>
            </select>
            <section v-if="selectedItemType">
                <component :is="newItem" :module="props.module" @close="doneAddingItem"></component>
            </section>
  
    </div>
</template>

<script setup lang="ts">

import { CourseModule } from '@/types';

import { ref, computed, defineAsyncComponent } from 'vue';
interface Props {
    module: CourseModule|null;
}
const props = defineProps<Props>();
const selectedItemType = ref<"overview" | "page" | "quiz" | "assignment" | "discussion" | null>(null);
const emit = defineEmits(['close']);
const itemsMap: Record<string, any> = {
    overview: defineAsyncComponent(() => import('@/components/Course/Module/Items/Overview/AddOverview.vue')),
    page: defineAsyncComponent(() => import('@/components/Course/Module/Items/Page/AddPage.vue')),
    quiz: defineAsyncComponent(() => import('@/components/Course/Module/Items/Quiz/AddQuiz.vue')),
    assignment: defineAsyncComponent(() => import('@/components/Course/Module/Items/Assignment/AddAssignment.vue')),
    discussion: defineAsyncComponent(() => import('@/components/Course/Module/Items/Discussion/AddDiscussion.vue')),
    wrap_up: defineAsyncComponent(() => import('@/components/Course/Module/Items/WrapUp/AddWrapUp.vue')),
};
const newItem = computed(() => {
    return selectedItemType.value ? itemsMap[selectedItemType.value] : null;
});
const doneAddingItem = () => {
    selectedItemType.value = null;
    emit('close');
};






  

</script>

<style scoped>

</style>