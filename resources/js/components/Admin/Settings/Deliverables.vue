<template>
    <div
        class="flex flex-col gap-6 rounded-xl border border-primary bg-base-100 p-6 shadow-lg shadow-primary"
    >
        <h2 class="text-3xl font-bold text-primary">Deliverables</h2>
        <button
            class="btn max-w-max border-primary bg-primary text-primary-content"
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
                                @change="deliverable.changed = true"
                                @keydown.esc="deliverable.changed = false"
                            />
                        </td>
                        <td>
                            <div class="flex flex-row items-center gap-2">
                                <button
                                    v-if="deliverable.changed && deliverable.id"
                                    type="button"
                                    @click="updateDeliverables(deliverable)"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="var(--color-success)"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-check-icon lucide-check"
                                    >
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    class="font-bold text-error"
                                    @click="() => removeDeliverable(index)"
                                >
                                    X
                                </button>
                            </div>
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
        changed?: boolean;
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
const updateDeliverables = (deliverable: Props['deliverables'][0]) => {
    const form = useForm({
        name: deliverable.name,
        template_days_offset: deliverable.template_days_offset,
    });
    form.post(`/admin/deliverables/${deliverable.id}`, {
        onSuccess: () => {
            deliverable.changed = false;
            router.reload();
        },
        onError: () => {
            alert('Failed to update deliverables.');
        },
    });
};
</script>

<style scoped></style>
