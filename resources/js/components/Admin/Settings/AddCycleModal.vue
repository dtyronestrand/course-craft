<template>
  <dialog ref="modal" class="modal">
    <div class="modal-box bg-base-100 text-base-content p-4 border border-primary">
      <h2 class="text-3xl">Add New Cycle</h2>
      <p class="text-sm">Enter the start and end dates for the current development cycle.</p>
      <form @submit.prevent="handleSubmit" class="mt-8 flex flex-col gap-4">
        <label for="cycleName">Cycle</label>
        <input class="bg-accent text-accent-content border-b border-primary" type="text" name="cycleName" v-model="cycleName" />
        <label for="start">Start Date:</label>
        <input class="bg-accent text-accent-content border-b border-primary" type="date" name="start" v-model="cycleStart" />
        <label for="end">End Date:</label>
        <input class="bg-accent text-accent-content border-b border-primary" type="date" name="end" v-model="cycleEnd" />
        <div class="flex gap-2">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-error" @click="close">Cancel</button>
        </div>
      </form>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button @click="close">close</button>
    </form>
  </dialog>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

interface Props {
  show: boolean;
}

const props = defineProps<Props>();
const emit = defineEmits(['close']);

const modal = ref<HTMLDialogElement | null>(null);
const cycleName = ref('');
const cycleStart = ref('');
const cycleEnd = ref('');

watch(() => props.show, (newVal) => {
  if (newVal) {
    modal.value?.showModal();
  } else {
    modal.value?.close();
  }
});

const close = () => {
  cycleName.value = '';
  cycleStart.value = '';
  cycleEnd.value = '';
  emit('close');
};

const handleSubmit = () => {
  const form = useForm({
    cycle_name: cycleName.value,
    cycle_start: cycleStart.value,
    cycle_end: cycleEnd.value,
  });
  form.post('/admin/settings', {
    onSuccess: () => {
      close();
      router.reload();
    },
  });
};
</script>
