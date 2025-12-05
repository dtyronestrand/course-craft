<template>
    <div v-if="props.isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="glass-effect w-full max-w-md  rounded-lg border border-primary p-6 shadow-lg shadow-primary" ref="target">
            <div class="flex flex-row items-center gap-4 pb-8">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border border-primary bg-primary/10 text-primary frosted-backdrop">
                    <h1 class="text-md font-bold text-center">{{ course.prefix }} {{ course.number }}</h1>
                </div>
                <div class="flex flex-col">
                <p class="text-lg font-semibold">{{ course.title }}</p>
               <ul class="mt-4 flex flex-row gap-2">
                <li class="flex flex-col items-center justify-center gap-2" v-for="user in course.users" :key="user.id">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full border border-primary bg-primary/10 text-primary shadow shadow-primary frosted-backdrop p-4">
                        {{ getInitials(user.first_name, user.last_name) }}
                    </div>
                    <p class="text-xs">{{ user.pivot.role }}</p>
                </li>
            </ul>
                </div>
            </div>
            <div v-if="hasMissedDeliverables" class="my-4">
          
                <h3 class="font-semibold">Missed Deliverables:</h3>
                <ul class="list-inside list-disc">
                    <li v-for="deliverable in course.deliverables" :key="deliverable.id"><span v-if="deliverable.pivot.missed_due_date_count > 0">{{ deliverable.name }}</span></li>
                
                </ul>
            </div>
         
            <button @click.stop="emit('modal-close')" class="btn btn-primary mt-4 w-full">Close</button>
        </div>
    </div>
   


</template>
<script setup lang="ts">
import {defineEmits, ref, computed} from 'vue';
import {onClickOutside} from '@vueuse/core';
import { useInitials } from '@/composables/useInitials';
const { getInitials } = useInitials();
const props = defineProps<{
    isOpen: boolean,
    course: any
  
}>();

const emit = defineEmits<{"modal-close": []}>()

const target = ref(null);
onClickOutside(target, () => {
    emit("modal-close");
});
const hasMissedDeliverables = computed(() => {
    return props.course.deliverables.some((deliverable: any) => deliverable.pivot.missed_due_date_count > 0);
});
</script>

<style scoped>

</style>