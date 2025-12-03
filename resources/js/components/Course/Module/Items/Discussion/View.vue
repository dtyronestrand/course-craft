<template>
    <div class="prose mt-4 max-w-none border" v-if="!isEditing">
        <details>
            <summary
                class="flex w-full flex-row items-center justify-between border-b border-secondary bg-primary p-4 text-xl"
            >
                <h4 class="text-primary-content">
                    Discussion: {{ module.items[index].itemable?.title }}
                </h4>
                <div class="flex flex-row gap-4">
                    <EditButton
                        background="success"
                        @click="isEditing = true"
                    />
                    <DeleteButton @click="deleteDiscussion" />
                </div>
            </summary>
            <div class="prose max-w-none">
                <h5 class="w-full border-t border-b bg-neutral p-4 text-lg">
                    Prompt
                </h5>
                <div
                    class="p-4"
                    v-html="module.items[index].itemable?.prompt"
                ></div>

                <h5 class="w-full border-t border-b bg-neutral p-4 text-lg">
                    Settings
                </h5>
                <div class="p-4">
                    <p>
                        Graded: {{ discussionSettings?.graded ? 'Yes' : 'No' }}
                    </p>
                    <p v-if="discussionSettings?.graded">
                        Point Value: {{ discussionSettings?.point_value }}
                    </p>
                </div>
            </div>
        </details>
    </div>
    <div v-else>
        <form
            @submit.prevent="updateDiscussion"
            class="border border-secondary"
        >
            <input
                v-model="form.title"
                type="text"
                class="input mt-2 mb-4 h-full w-full max-w-md bg-base-300 p-4 text-3xl"
            />
            <h4 class="p-4 text-2xl">Prompt</h4>
            <TipTap v-model="form.prompt" />

            <h4 class="p-4 text-2xl">Settings</h4>
            <div class="p-4">
                <label for="graded">Graded</label>
                <input
                    type="checkbox"
                    v-model="form.settings.graded"
                    id="graded"
                    class="checkbox-bordered checkbox mx-4"
                />

                <input
                    v-if="form.settings.graded"
                    v-model="form.settings.point_value"
                    type="number"
                    class="input-bordered input mx-4 mt-2 mb-4 w-full max-w-xs"
                    placeholder="Point Value"
                />
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
import { CourseModule } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import CancelButton from '../Buttons/CancelButton.vue';

const props = defineProps<{
    module: CourseModule;
    index: number;
}>();

const isEditing = ref(false);

// Cast settings to any for the discussion view so template won't error on union types
const discussionSettings = computed(() => {
    return props.module.items[props.index].itemable?.settings as any;
});

const form = useForm({
    title: props.module.items[props.index].itemable?.title || '',
    prompt: props.module.items[props.index].itemable?.prompt || '',
    settings: (props.module.items[props.index].itemable?.settings as any) || {
        point_value: 0,
        graded: false,
    },
});

const updateDiscussion = () => {
    form.put(
        `/course_discussions/${props.module.items[props.index].itemable_id}`,
        {
            onSuccess: () => {
                isEditing.value = false;
            },
        },
    );
};

const deleteDiscussion = () => {
    form.delete(
        `/course_discussions/${props.module.items[props.index].itemable_id}`,
    );
};
</script>

<style scoped></style>
