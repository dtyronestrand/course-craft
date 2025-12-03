<template>
    <div class="prose mt-4 max-w-none border" v-if="!isEditing">
        <details>
            <summary
                class="flex w-full flex-row items-center justify-between border-b border-secondary bg-primary p-4 text-xl"
            >
                <h4 class="text-primary-content">
                    Page: {{ props.item.itemable?.title }}
                </h4>
                <div class="flex flex-row gap-4">
                    <EditButton
                        background="success"
                        @click="isEditing = true"
                    />
                    <DeleteButton @click="deletePage" />
                </div>
            </summary>
            <div class="p-4" v-html="props.item.itemable?.content"></div>
        </details>
    </div>
    <div v-else class="mt-4 border">
        <form @submit.prevent="updatePage">
            <div
                class="mb-4 flex w-full flex-row items-center gap-4 border-b border-secondary bg-primary p-4 text-xl"
            >
                <h4 class="m-0">Page:</h4>
                <input
                    v-model="localItemable.title"
                    type="text"
                    class="input-bordered input"
                />
            </div>

            <TipTap v-model="localItemable.content" />

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
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import CancelButton from '../Buttons/CancelButton.vue';

interface Props {
    module: CourseModule;
    item: any;
    index: number;
}
const props = defineProps<Props>();

const isEditing = ref(false);

// Create a local reactive copy for editing
const localItemable = ref({
    title: props.item.itemable.title,
    content: props.item.itemable.content,
});

const form = useForm({
    title: localItemable.value.title,
    content: localItemable.value.content,
});

const updatePage = () => {
    form.title = localItemable.value.title;
    form.content = localItemable.value.content;
    form.put(`/course_pages/${props.item.itemable_id}`, {
        onSuccess: () => {
            isEditing.value = false;
        },
    });
};
const deletePage = () => {
    form.delete(`/course_pages/${props.item.itemable_id}`);
};
</script>

<style scoped></style>
