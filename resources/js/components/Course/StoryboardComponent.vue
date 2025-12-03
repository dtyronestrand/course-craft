<template>
    <div class="mx-20 my-12 flex flex-col">
        <h2 class="mb-8 text-5xl">
            {{ props.course.prefix }} {{ props.course.number }}: Storyboard
        </h2>
        <div v-for="module in props.course.modules" :key="module.id">
            <details class="mb-8 rounded-lg border p-4">
                <summary class="mb-2 text-3xl">
                    <h2>Module {{ module.order_index }}: {{ module.title }}</h2>
                </summary>

                <section
                    class="prose max-w-none"
                    v-if="module.items && module.items.length > 0"
                >
                    <article
                        v-for="(item, index) in module.items"
                        :key="item.id"
                        class="mb-4 rounded-lg"
                    >
                        <component
                            :is="getComponentForItem(item.itemable_type)"
                            :module="module"
                            :item="item"
                            :index="index"
                        />
                    </article>
                </section>
                <section v-else>
                    <article class="mb-4 rounded-lg border p-4">
                        <p class="italic">No items in this module yet.</p>
                    </article>
                </section>
                <button
                    @click="addItem(module)"
                    class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier-(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-info px-[1.5em] py-[0.25em] text-center font-bold text-info-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                >
                    Add Item to Module
                </button>
                <AddItem
                    v-if="showAddItemForm && moduleToAddItem?.id === module.id"
                    :module="module"
                    @close="closeAddItem"
                />
            </details>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Course, CourseModule } from '@/types';
import { defineAsyncComponent, ref } from 'vue';

const AddItem = defineAsyncComponent(
    () => import('@/components/Course/Module/Items/AddItem.vue'),
);
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
    overview: defineAsyncComponent(
        () => import('@/components/Course/Module/Items/Overview/View.vue'),
    ),
    page: defineAsyncComponent(
        () => import('@/components/Course/Module/Items/Page/View.vue'),
    ),
    assignment: defineAsyncComponent(
        () => import('@/components/Course/Module/Items/Assignment/View.vue'),
    ),
    discussion: defineAsyncComponent(
        () => import('@/components/Course/Module/Items/Discussion/View.vue'),
    ),
    quiz: defineAsyncComponent(
        () => import('@/components/Course/Module/Items/Quiz/View.vue'),
    ),
    wrap_up: defineAsyncComponent(
        () => import('@/components/Course/Module/Items/WrapUp/View.vue'),
    ),
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
summary {
    list-style: none;
    cursor: pointer;
}
</style>
