<template>
    <AdminLayout>
        <div class="flex grow flex-col">
            <h1 class="col-span-3 mb-4 text-center text-7xl">Settings</h1>
            <div class="grid grid-cols-2 gap-8 p-4">
                <div
                    class="rounded-xl border border-primary p-6 shadow-lg shadow-primary bg-base-100 text-base-content"
                >
                    <h2 class="text-3xl font-bold text-primary">
                        Development Cycles
                    </h2>
                    <button
                        @click="showModal = true"
                        class="bg-primary btn my-4 border-primary text-primary-content hover:bg-primary/50 active:bg-primary/30"
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
                                    <td>{{ cycle.start_date }}</td>
                                    <td>{{ cycle.end_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="bg-base-100 rounded-xl border border-primary p-6 shadow-lg shadow-primary"
                >
                    <h2 class="text-3xl font-bold text-primary">
                        Designer Capacity
                    </h2>
                    <div class="flex flex-col gap-4">
                        <input
                            v-model="capacity"
                            type="number"
                            class=" mt-8 w-min border border-primary bg-base-200 text-base-content p-2"
                            placeholder="0"
                        />
                        <button
                            class="bg-primary max-w-max rounded-md p-2 border-primary text-primary-content hover:bg-primary/50 active:bg-primary/30"
                            @click="updateCapacity"
                        >
                            Update Capacity
                        </button>
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

const page = usePage();
const showModal = ref(false);
const capacity = ref((page.props as any).capacity);
const updateCapacity = () => {
    router.post('/admin/settings', { capacity: capacity.value });
    router.reload({ only: ['capacity'] });
};
</script>

<style scoped></style>
