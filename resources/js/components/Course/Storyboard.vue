<template>
    <div class="flex flex-col mx-20" >
    <h2 class="text-5xl mb-4">{{ props.course.prefix }} {{ props.course.number }}: Storyboard</h2>
        <div v-for="module in props.course.modules" :key="module.id">
    
            <h3 class="text-3xl mb-4">Module {{ module.order_index }}: {{ module.title }}</h3>
          
            <section class="prose max-w-none" v-if="module.items && module.items.length > 0">
                <article v-for="(item, index) in module.items" :key="item.id" class=" rounded-lg p-4 mb-4">
                    <component :is="getComponentForItem(item.itemable_type)" :module="module" :item="item" :index="index" />
                </article>
            </section>
            <section v-else>
            <article class="border rounded-lg p-4 mb-4">
                <p class="italic">No items in this module yet.</p>
            </article>
            </section>
              <button @click="addItem(module)" class="btn btn-md mb-4 btn-info text-info-content">Add Item to Module</button>
            <AddItem v-if="showAddItemForm && moduleToAddItem?.id === module.id" :module="module" @close="closeAddItem" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Course, CourseModule } from '@/types';
import { ref, defineAsyncComponent } from 'vue';

const AddItem = defineAsyncComponent(() => import('@/components/Course/Module/Items/AddItem.vue'));
interface Props {
   course: Course;
    numberOfModules: number;
}
const showAddItemForm = ref(false);
const moduleToAddItem = ref<CourseModule | null>(null);

const props = defineProps<Props>();
const addItem = (module: any) => {
    showAddItemForm.value = true;
    moduleToAddItem.value = module;
};
const itemsMap: Record<string, any> = {
    'App\\Models\\ModuleOverview': defineAsyncComponent(() => import('@/components/Course/Module/Items/Overview/View.vue')),
    'App\\Models\\CoursePage': defineAsyncComponent(() => import('@/components/Course/Module/Items/Page/View.vue')),
    'App\\Models\\CourseAssignment': defineAsyncComponent(() => import('@/components/Course/Module/Items/Assignment/View.vue')),
    'App\\Models\\CourseDiscussion': defineAsyncComponent(() => import('@/components/Course/Module/Items/Discussion/View.vue')),
};

const getComponentForItem = (itemableType: string) => {
    return itemsMap[itemableType] || 'div';
};


const closeAddItem = () => {
    showAddItemForm.value = false;
    moduleToAddItem.value = null;
};
</script>

<style scoped>

</style>