<template>
    <AdminLayout>
    <div class="grid grid-cols-3 grid-rows-3 mx-12 gap-4">
<h1 class="text-7xl text-center col-span-3">Settings</h1>
<div class="bg-base-100 text-base-content rounded-2xl p-4 border border-primary col-span-1 ">
<h2 class="text-3xl">Development Cycles</h2>
<button @click="showModal = true" class="btn btn-info my-4">Add New Cycle</button>
<table class="w-full">
<thead >
<tr><th>Cycle Name</th>
<th>Start</th>
<th>End</th>
</tr>
</thead>
<tbody>
<tr v-for="cycle in (page.props as any).cycles" :key="cycle.id">
<td>{{ cycle.name  }}</td>
<td>{{ cycle.start_date }}</td>
<td>{{ cycle.end_date }}</td>
</tr>
</tbody>
</table>
</div>

        <Deliverables :deliverables="(page.props as any).deliverables || []" class="col-span-2 row-span-3"/>
    </div>
    <AddCycleModal :show="showModal" @close="showModal = false" />
    </AdminLayout>
</template>

<script setup lang="ts">
import Deliverables from '@/components/Admin/Settings/Deliverables.vue';
import AddCycleModal from '@/components/Admin/Settings/AddCycleModal.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const showModal = ref(false);
</script>

<style scoped>

</style>