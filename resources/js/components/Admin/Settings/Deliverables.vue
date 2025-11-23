<template>
    <form @submit.prevent="submitDeliverables" class="col-span-2 row-span-3 bg-base-100 text-base-content rounded-2xl p-4 border border-primary">
    <h2 class="text-3xl">Deliverables</h2>
        <button class="btn btn-info" @click="addDeliverable">Add Deliverable</button>
        <table class="table w-full mt-4">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Template Days Offset</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(deliverable, index) in deliverables" :key="index">
                    <td><input type="text" v-model="deliverable.name"/></td>
                    <td><input type="number" v-model="deliverable.template_days_offset"/></td>
                </tr>
            </tbody>
        </table>
    </form>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {useForm, router} from '@inertiajs/vue3';
    interface Props {
        deliverables: {
            name: string;
            template_days_offset: number;
        }[];
    }
    const props = defineProps<Props>();
    const deliverables = ref(props.deliverables);
    const addDeliverable = () => {
        deliverables.value.push({
            name: '',
            template_days_offset: 0,
        });
    };
   const  form = useForm({
         deliverables: deliverables.value,
   })

const submitDeliverables = () => {
    form.deliverables = deliverables.value;
    form.post('/admin/deliverables', {
        onSuccess: () => {
            alert('Deliverables updated successfully!');
            router.reload()
        },
        onError: () => {
            alert('Failed to update deliverables.');
        },
    });
};
</script>

<style scoped>

</style>