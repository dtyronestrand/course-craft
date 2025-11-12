<template>
    <div class="flex flex-col my-12 mx-20" >
    <h2 class="text-5xl mb-8">{{ props.course.prefix }} {{ props.course.number }}: Storyboard</h2>
        <div v-for="module in props.course.modules" :key="module.id">
    <div class="border rounded-lg p-4 mb-8">
            <h3 class="text-3xl mb-2">Module {{ module.order_index }}: {{ module.title }}</h3>
          
            <section class="prose max-w-none" v-if="module.items && module.items.length > 0">
                <article v-for="(item, index) in module.items" :key="item.id" class=" rounded-lg mb-4">
                    <component :is="getComponentForItem(item.itemable_type)" :module="module" :item="item" :index="index" />
                </article>
            </section>
            <section v-else>
            <article class="border rounded-lg p-4 mb-4">
                <p class="italic">No items in this module yet.</p>
            </article>
            </section>
              <button @click="addItem(module)" class="appearance-none bg-info border-[0.125em] border-secondary rounded-[0.9375em] box-border text-info-content cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] translate-y-[-2px] active:shadow-none active:translate-y-0">Add Item to Module</button>
            <AddItem v-if="showAddItemForm && moduleToAddItem?.id === module.id" :module="module" @close="closeAddItem" />
        </div>
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
    'overview': defineAsyncComponent(() => import('@/components/Course/Module/Items/Overview/View.vue')),
    'page': defineAsyncComponent(() => import('@/components/Course/Module/Items/Page/View.vue')),
    'assignment': defineAsyncComponent(() => import('@/components/Course/Module/Items/Assignment/View.vue')),
    'discussion': defineAsyncComponent(() => import('@/components/Course/Module/Items/Discussion/View.vue')),
    'quiz': defineAsyncComponent(() => import('@/components/Course/Module/Items/Quiz/View.vue')),
    'wrap_up': defineAsyncComponent(() => import('@/components/Course/Module/Items/WrapUp/View.vue')),
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