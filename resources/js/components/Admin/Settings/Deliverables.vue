<template>
  <div
    class="glass-effect rounded-xl p-6 flex flex-col gap-6 border border-primary/70 shadow-lg shadow-primary/20"
  >
    <h2 class="text-3xl text-primary font-bold">Deliverables</h2>
    <button class="btn bg-info/10 border-info text-info shadow-md shadow-info/10 w-max" @click="addDeliverable">Add Deliverable</button>

    <form class="table w-full mt-4">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Template Days Offset</th>
          </tr>
        </thead>
        <tbody>

          <tr v-for="(deliverable, index) in deliverables" :key="index">
            <td><input type="text" v-model="deliverable.name" /></td>
            <td><input type="number" v-model="deliverable.template_days_offset" /></td>
            <td><button class="btn bg-error/10 text-error border-error  shadow-md shadow-error " @click="() => removeDeliverable(index)">Remove</button></td>
          </tr>
        </tbody>
      </table>
        <button v-if="newDeliverable" type="button" class="btn btn-success mt-4" @click="submitDeliverables">
            Save Deliverables</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
interface Props {
  deliverables: {
    id?: number;
    name: string;
    template_days_offset: number;
  }[];
}
const props = defineProps<Props>();
const deliverables = ref(props.deliverables);
const newDeliverable = ref(false);
const addDeliverable = () => {
  deliverables.value.push({
    name: "",
    template_days_offset: 0,
  });
    newDeliverable.value = true;
};

const submitDeliverables = () => {
  const filtered = deliverables.value
    .filter(d => !d.id && d.name && d.name.trim() !== "")
    .map(d => ({
      name: d.name,
      template_days_offset: d.template_days_offset,
    }));
  const form = useForm({
    deliverables: filtered,
  });
  form.post("/admin/deliverables", {
    onSuccess: () => {
      newDeliverable.value = false;
      router.reload();
    },
    onError: () => {
      alert("Failed to update deliverables.");
    },
  });
};

const removeDeliverable = (index: number) => {
  const deliverable = deliverables.value[index];
  if (deliverable.id) {
    router.delete(`/admin/deliverables/${deliverable.id}`, {
      onSuccess: () => {
        router.reload();
      },
    });
  } else {
    deliverables.value.splice(index, 1);
  }
};
</script>

<style scoped></style>
