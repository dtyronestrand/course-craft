<template>
    <div class="prose mt-4 max-w-none border" v-if="!isEditing">
        <details>
            <summary
                class="flex w-full flex-row items-center justify-between border-b border-secondary bg-primary p-4 text-xl"
            >
                <h4 class="text-primary-content">
                    Assignment: {{ module.items[index].itemable?.title }}
                </h4>
                <div class="flex flex-row gap-4">
                    <EditButton
                        background="success"
                        @click="isEditing = true"
                    />
                    <DeleteButton @click="deleteAssignment" />
                </div>
            </summary>
            <div class="prose max-w-none">
                <h5 class="w-full border-t border-b bg-neutral p-4 text-lg">
                    Purpose
                </h5>
                <div
                    class="p-4"
                    v-html="module.items[index].itemable?.purpose"
                ></div>
            </div>
            <h5 class="w-full border-t border-b bg-neutral p-4 text-lg">
                Criteria
            </h5>
            <div
                class="p-4"
                v-html="module.items[index].itemable?.criteria"
            ></div>
            <h5 class="w-full border-t border-b bg-neutral p-4 text-lg">
                Settings
            </h5>
            <div class="p-4">
                <p>
                    Point Value:
                    {{ module.items[index].itemable?.settings?.point_value }}
                </p>
                <p>
                    Submission Types:
                    {{
                        (
                            module.items[index].itemable
                                ?.settings as AssignmentSettings
                        )?.submission_type?.join(', ')
                    }}
                </p>
            </div>
        </details>
    </div>
    <div v-else>
        <form
            @submit.prevent="updateAssignment"
            class="border border-secondary"
        >
            <input
                v-model="form.title"
                type="text"
                class="input mt-2 mb-4 h-full w-full max-w-md bg-base-300 p-4 text-3xl"
            />
            <h4 class="p-4 text-2xl">Purpose</h4>
            <TipTap v-model="form.purpose" />
            <h4 class="p-4 text-2xl">Criteria</h4>
            <TipTap v-model="form.criteria" />
            <h4 class="p-4 text-2xl">Settings</h4>
            <div class="p-4">
                <label
                    for="point_value"
                    class="mx-4 mb-4 flex items-center space-x-2"
                    >Point Value:
                </label>
                <input
                    v-model="form.settings.point_value"
                    type="number"
                    class="input-bordered input mx-4 mt-2 mb-4 w-full max-w-xs"
                    placeholder="Point Value"
                />
                <label
                    for="submission_type"
                    class="mx-4 mb-4 flex items-center space-x-2"
                    >Submision Type:
                </label>
                <select
                    multiple
                    v-model="form.settings.submission_type"
                    id="submission_type"
                    class="select-bordered select mx-4 w-full max-w-xs"
                >
                    <option value="file_upload">File Upload</option>
                    <option value="text_entry">Text Entry</option>
                    <option value="url_submission">URL Submission</option>
                </select>
            </div>
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
import { AssignmentSettings, CourseModule } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import CancelButton from '../Buttons/CancelButton.vue';

const props = defineProps<{
    module: CourseModule;
    index: number;
}>();

const isEditing = ref(false);

const form = useForm({
    title: props.module.items[props.index].itemable?.title || '',
    purpose: props.module.items[props.index].itemable?.purpose || '',
    criteria: props.module.items[props.index].itemable?.criteria || '',
    settings: (props.module.items[props.index].itemable
        ?.settings as AssignmentSettings) || {
        point_value: 0,
        submission_type: [],
    },
});

const updateAssignment = () => {
    form.put(
        `/course_assignments/${props.module.items[props.index].itemable_id}`,
        {
            onSuccess: () => {
                isEditing.value = false;
            },
        },
    );
};

const deleteAssignment = () => {
    form.delete(
        `/course_assignments/${props.module.items[props.index].itemable_id}`,
    );
};
</script>

<style scoped></style>
