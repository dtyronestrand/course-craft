<template>
    <AdminLayout>
        <div class="flex grow flex-col">
            <h1 class="col-span-3 mb-4 text-center text-7xl">Settings</h1>
            <div class="grid grid-cols-2 gap-8 p-4">
                <div
                    class="rounded-xl border border-primary bg-base-100 p-6 text-base-content shadow-lg shadow-primary"
                >
                    <h2 class="text-3xl font-bold text-primary">
                        Development Cycles
                    </h2>
                    <button
                        @click="showModal = true"
                        class="btn my-4 border-primary bg-primary text-primary-content hover:bg-primary/50 active:bg-primary/30"
                    >
                        Add New Cycle
                    </button>
                    <div class="grow">
                        <table class="w-full">
                            <thead class="border-b border-primary text-left">
                                <tr>
                                    <th>Cycle Name</th>
                                    <th>Start</th>
                                    <th>End</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="mt-4"
                                    v-for="cycle in (page.props as any).cycles"
                                    :key="cycle.id"
                                >
                                    <td>{{ cycle.name }}</td>
                                    <td>{{ dayjs(cycle.start_date).format('MM/DD/YYYY') }}</td>
                                    <td>{{ dayjs(cycle.end_date).format('MM/DD/YYYY') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="rounded-xl border border-primary bg-base-100 p-6 shadow-lg shadow-primary"
                >
                    <h2 class="text-3xl font-bold text-primary">
                        Designer Capacity
                    </h2>
                    <div class="flex flex-col gap-4">
                        <input
                            v-model="capacity"
                            type="number"
                            class="mt-8 w-min border border-primary bg-base-200 p-2 text-base-content"
                            placeholder="0"
                            @change="capacityChanged = true"
                        />
                        <div v-if="capacityChanged" class="flex flex-row gap-4">
                        <button
                            class="max-w-max rounded-md  bg-success p-2 text-success-content hover:bg-[oklch(from var(--color-primary) o k )]active:bg-success/50"
                            @click="updateCapacity"
                        >
                            Update Capacity
                        </button>
                        <button
                            class="max-w-max rounded-md bg-error p-2 text-error-content hover:bg-error/30 active:bg-error/50"
                            @click="
                                capacityChanged = false;
                                capacity = (page.props as any).capacity;
                            ">Cancel</button>
                        </div>
                    </div>
                </div>
                <Deliverables
                    :deliverables="(page.props as any).deliverables || []"
                />
            </div>
            <AddCycleModal :show="showModal" @close="showModal = false" />
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import AddCycleModal from '@/components/Admin/Settings/AddCycleModal.vue';
import Deliverables from '@/components/Admin/Settings/Deliverables.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import dayjs from 'dayjs';

const page = usePage();
const showModal = ref(false);
const capacity = ref((page.props as any).capacity);
const capacityChanged = ref(false);
const updateCapacity = () => {
    router.post('/admin/settings', { capacity: capacity.value });
    router.reload({ only: ['capacity'] });
    capacityChanged.value = false;
};
</script>

<style scoped></style>
