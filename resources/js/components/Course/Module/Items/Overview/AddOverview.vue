<template>
    <form class="border border-secondary">
        <h3 class="border-b border-secondary bg-primary p-4 text-3xl">
            Module {{ props.module.order_index }}
            {{ props.module.title }} Overview
        </h3>
        <h4 class="p-4 text-2xl">Overview Content</h4>
        <TipTap v-model="overviewData.content" />
        <h4 class="p-2 text-2xl">Learning Objectives</h4>
        <ol class="">
            <li
                v-for="(objective, index) in props.module.module_objectives"
                :key="index"
            >
                {{ props.module.order_index }}.{{ objective.number }}:
                {{ objective.objective }}
            </li>
        </ol>
        <SaveButton @click.prevent="overviewSubmit" />
        <CancelButton @click="$emit('close')" />
    </form>
</template>

<script setup lang="ts">
import TipTap from '@/components/TipTap.vue';
import { CourseModule } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import CancelButton from '../Buttons/CancelButton.vue';
import SaveButton from '../Buttons/SaveButton.vue';
interface Props {
    module: CourseModule;
}
const props = defineProps<Props>();
const overviewData = ref({
    content: '',
});
const form = useForm({
    content: overviewData.value.content,
    module: props.module.id,
});

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const overviewSubmit = () => {
    form.content = overviewData.value.content;
    form.post('/module_overviews', {
        onSuccess: () => {
            emit('close');
        },
    });
};
</script>

<style scoped></style>
