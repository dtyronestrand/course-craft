<template>
    <AdminLayout>
    <div class="grid grid-cols-3 grid-rows-3 mx-12 gap-4">
<h1 class="text-7xl text-center col-span-3">Settings</h1>
<div class="bg-base-100 text-base-content rounded-2xl p-4 border border-primary col-span-1 row-span-1">
<h2 class="text-3xl">Cycle Dates</h2>
<p class="text-sm">Enter the start and end dates for the current development cycle. </p>
<form @submit.prevent="updateSettings" class="mt-8 flex flex-col gap-4">
<div class="flex flex-row
">
<label for="start">Start Date:</label>
<input class="ml-2 mr-4" type="date" name="start"  v-model="localCycleStart" />
<label for="end">End Date:</label>
<input class="ml-2" type="date" name="end "  v-model="localCycleEnd" />
</div>
<button type="submit" class="btn btn-info">Update</button>
</form>
    </div>
        <Deliverables :deliverables="(page.props as any).deliverables || []" class="col-span-2 row-span-3"/>
    </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import Deliverables from '@/components/Admin/Settings/Deliverables.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import {usePage, router, useForm} from '@inertiajs/vue3';    
import {ref} from 'vue';

const page = usePage();

const localCycleStart = ref((page.props as any).settings?.cycle_start || '');
const localCycleEnd = ref((page.props as any).settings?.cycle_end || '');

const form = useForm({
    cycle_start: localCycleStart.value,
    cycle_end: localCycleEnd.value,
});

const updateSettings = () => {
    form.cycle_start = localCycleStart.value;
    form.cycle_end = localCycleEnd.value;
    form.post('/admin/settings', {
        onSuccess: () => {
            alert('Settings updated successfully!');
            router.reload();
        },
        onError: () => {
            alert('Failed to update settings.');
        },
    });
};
</script>

<style scoped>

</style>