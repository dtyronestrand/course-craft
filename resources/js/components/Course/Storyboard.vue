<template>
    <div class="flex flex-col mx-20" >
    <h2 class="text-5xl mb-4">{{ props.course.prefix }} {{ props.course.number }}: Storyboard</h2>
        <div v-for="module in props.course.modules" :key="module.id">
            <h3 class="text-3xl mb-4">Module {{ module.order_index }}: {{ module.title }}</h3>
            <button class="btn btn-md btn-info text-info-content">Add Item to Module</button>
            <section v-if="module.items.length >0">
                <article v-for="item in module.items" :key="item.id" class="border rounded-lg p-4 mb-4">
                    <h4 class="text-2xl mb-2">{{ item.title }}</h4>
                    <p class="mb-2">{{ item.description }}</p>
                    <p class="italic text-sm">Type: {{ item.type }}</p>
                </article>
            </section>
            <section v-else>
            <article class="border rounded-lg p-4 mb-4">
                <p class="italic">No items in this module yet.</p>
            </article>
            </section>
        </div>
    </div>

</template>

<script setup lang="ts">
import { Course } from '@/types';
interface Props {
    course: Course;
    numberOfModules: number;
}
const props = defineProps<Props>();
</script>

<style scoped>

</style>