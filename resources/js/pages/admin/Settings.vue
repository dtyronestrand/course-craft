<template>
    <AdminLayout>
    <div class="grow flex flex-col ">
<h1 class="text-7xl text-center col-span-3 mb-4">Settings</h1>
    <div class="grid grid-cols-2 gap-8 p-4">
<div class="glass-effect rounded-xl p-6 border border-primary/70 shadow-lg shadow-primary/20">
<h2 class="text-3xl text-primary font-bold">Development Cycles</h2>
<button @click="showModal = true" class="btn frosted-backdrop bg-info/20 border-info text-info shadow-md shadow-info/20 my-4">Add New Cycle</button>
<div class="grow">
<table class="w-full">
<thead class="text-left border-b border-primary ">
<tr><th>Cycle Name</th>
<th>Start</th>
<th>End</th>
</tr>
</thead>
<tbody>
<tr class="mt-4" v-for="cycle in (page.props as any).cycles" :key="cycle.id">
<td>{{ cycle.name  }}</td>
<td>{{ cycle.start_date }}</td>
<td>{{ cycle.end_date }}</td>
</tr>
</tbody>
</table>
</div>
</div>
<div class="glass-effect rounded-xl p-6 border border-primary/70 shadow-lg shadow-primary/20">
<h2 class="text-3xl font-bold text-primary">Designer Capacity</h2>
<div class="flex flex-col gap-4">
<input v-model="capacity" type="number" class="frosted-backdrop p-2 bg-primary/20 mt-8 w-min border border-primary/50" placeholder="0"/>
<button class="frosted-backdrop btn w-max text-info bg-info/20 border-info shadow-md shadow-info-20" @click="updateCapacity">Update Capacity</button>
</div>
</div>
        <Deliverables :deliverables="(page.props as any).deliverables || []" />
    </div>
    <AddCycleModal :show="showModal" @close="showModal = false" />
    </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import Deliverables from '@/components/Admin/Settings/Deliverables.vue';
import AddCycleModal from '@/components/Admin/Settings/AddCycleModal.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const showModal = ref(false);
const capacity = ref((page.props as any).capacity);
const updateCapacity = ()=> {
router.post('/admin/settings', { capacity: capacity.value });
router.reload({ only: ['capacity'] });
}
</script>

<style scoped>

</style>