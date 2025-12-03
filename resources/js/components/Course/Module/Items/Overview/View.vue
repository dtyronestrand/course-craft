<template>
    <div class="prose mt-4 max-w-none border" v-if="!isEditing">
        <details>
            <summary
                class="flex w-full flex-row items-center justify-between border-b border-secondary bg-primary p-4 text-xl"
            >
                <h4 class="text-primary-content">
                    Module {{ module.order_index }} {{ module.title }} Overview
                </h4>
                <div class="flex flex-row gap-4">
                    <EditButton
                        background="success"
                        @click="isEditing = true"
                    />
                    <DeleteButton @click="deleteOverview" />
                </div>
            </summary>
            <div
                class="p-4"
                v-html="module.items[index].itemable.content"
            ></div>
            <div class="prose max-w-none">
                <h5 class="w-full border-t border-b bg-neutral p-4 text-lg">
                    Learning Objectives
                </h5>
                <ol class="list-inside list-none">
                    <li
                        v-for="(
                            objective, objIndex
                        ) in module.module_objectives"
                        :key="objIndex"
                    >
                        {{ module.order_index }}.{{ objective.number }}:
                        {{ objective.objective }}
                    </li>
                </ol>
            </div>
        </details>
    </div>
    <div v-else>
        <form @submit.prevent="updateOverview" class="border border-secondary">
            <h3 class="border-b border-secondary bg-primary p-4 text-3xl">
                Module {{ module.order_index }} {{ module.title }} Overview
            </h3>
            <h4 class="p-4 text-2xl">Overview Content</h4>
            <TipTap v-model="form.content" />
            <div class="flex flex-row gap-4 p-4">
                <UpdateButton
                    :itemType="props.module.items[index].itemable_type"
                />
                <CancelButton @click="isEditing = false" />
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import UpdateButton from '@/components/Course/Module/Items/Buttons/UpdateButton.vue';
import DeleteButton from '@/components/DeleteButton.vue';
import EditButton from '@/components/EditButton.vue';
import TipTap from '@/components/TipTap.vue';
import { CourseModule } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import CancelButton from '../Buttons/CancelButton.vue';
const props = defineProps<{
    module: CourseModule;
    item: any;
    index: number;
}>();

const isEditing = ref(false);

const form = useForm({
    content: props.module.items[props.index].itemable.content,
});

const updateOverview = () => {
    form.put(
        `/module_overviews/${props.module.items[props.index].itemable_id}`,
        {
            onSuccess: () => {
                isEditing.value = false;
            },
        },
    );
};

const deleteOverview = () => {
    router.delete(
        `/module_overviews/${props.module.items[props.index].itemable_id}`,
        {
            onSuccess: () => {
                router.reload();
            },
        },
    );
};
</script>

<style scoped></style>
