<template>
    <dialog ref="modal" class="modal">
        <div
            class="modal-box border border-primary bg-base-100 p-4 text-base-content shadow shadow-primary"
        >
            <h2 class="text-3xl">Add New Cycle</h2>
            <p class="text-sm">
                Enter the start and end dates for the current development cycle.
            </p>
            <form
                @submit.prevent="handleSubmit"
                class="mt-8 flex flex-col gap-4"
            >
                <label for="cycleName">Cycle</label>
                <input
                    class="border-b border-primary bg-base-200 text-base-content"
                    type="text"
                    name="cycleName"
                    v-model="cycleName"
                />
                <label for="start">Start Date:</label>
                <input
                    class="border-b border-primary bg-base-200 text-base-content"
                    type="date"
                    name="start"
                    v-model="cycleStart"
                />
                <label for="end">End Date:</label>
                <input
                    class="border-b border-primary bg-base-200 text-base-content"
                    type="date"
                    name="end"
                    v-model="cycleEnd"
                />
                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="hover:bg-succes/30 btn text-success-content btn-success active:bg-success/50"
                    >
                        Save
                    </button>
                    <button
                        type="button"
                        class="btn text-error-content btn-error hover:bg-error/30 active:bg-error/50"
                        @click="close"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button @click="close">close</button>
        </form>
    </dialog>
</template>

<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Props {
    show: boolean;
}

const props = defineProps<Props>();
const emit = defineEmits(['close']);

const modal = ref<HTMLDialogElement | null>(null);
const cycleName = ref('');
const cycleStart = ref('');
const cycleEnd = ref('');

watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            modal.value?.showModal();
        } else {
            modal.value?.close();
        }
    },
);

const close = () => {
    cycleName.value = '';
    cycleStart.value = '';
    cycleEnd.value = '';
    emit('close');
};

const handleSubmit = () => {
    const form = useForm({
        name: cycleName.value,
        start_date: cycleStart.value,
        end_date: cycleEnd.value,
    });
    form.post('/admin/development-cycles', {
        onSuccess: () => {
            close();
            router.reload();
        },
    });
};
</script>
