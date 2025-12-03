<template>
    <div
        class="glass-effect flex flex-col gap-6 rounded-xl border border-primary/70 p-6 shadow-lg shadow-primary/20"
    >
        <h2 class="text-3xl font-bold text-primary">Deliverables</h2>
        <button
            class="frosted-backdrop btn w-max border-info bg-info/10 text-info shadow-md shadow-info/10"
            @click="addDeliverable"
        >
            Add Deliverable
        </button>

        <form class="table mt-4 w-full">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Template Days Offset</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(deliverable, index) in deliverables"
                        :key="index"
                    >
                        <td>
                            <input type="text" v-model="deliverable.name" />
                        </td>
                        <td>
                            <input
                                type="number"
                                v-model="deliverable.template_days_offset"
                            />
                        </td>
                        <td>
                            <button
                                class="frosted-backdrop btn border-error bg-error/10 font-bold text-error shadow-md shadow-error"
                                @click="() => removeDeliverable(index)"
                            >
                                X
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button
                v-if="newDeliverable"
                type="button"
                class="btn mt-4 btn-success"
                @click="submitDeliverables"
            >
                Save Deliverables
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
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
        name: '',
        template_days_offset: 0,
    });
    newDeliverable.value = true;
};

const submitDeliverables = () => {
    const filtered = deliverables.value
        .filter((d) => !d.id && d.name && d.name.trim() !== '')
        .map((d) => ({
            name: d.name,
            template_days_offset: d.template_days_offset,
        }));
    const form = useForm({
        deliverables: filtered,
    });
    form.post('/admin/deliverables', {
        onSuccess: () => {
            newDeliverable.value = false;
            router.reload();
        },
        onError: () => {
            alert('Failed to update deliverables.');
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
