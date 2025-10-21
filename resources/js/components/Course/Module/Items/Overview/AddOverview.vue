<template>
    <div class="border border-secondary ">
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Module {{ props.module.order_index }} {{ props.module.title  }} Overview</h3>
        <h4 class="p-4 text-2xl">Overview Content</h4>
        <TipTap />
        <h4 class="p-2 text-2xl">Learning Objectives</h4>
        <ol class="">
            <li v-for="(objective, index) in props.module.module_objectives" :key="index">
                {{ props.module.order_index }}.{{ objective.number }}: {{ objective.objective }}
            </li>
        </ol>
    </div>
</template>

<script setup lang="ts">
import { CourseModule } from '@/types';
import { ref } from 'vue';
import TipTap from '@/components/TipTap.vue';
interface Props {
    module: CourseModule;
}
const overviewData = ref({
    content: '',
})
const props = defineProps<Props>();
const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'overviewAdded', overviewData: any): void;
}>();
</script>

<style scoped>

</style>