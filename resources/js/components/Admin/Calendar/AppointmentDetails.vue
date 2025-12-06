<template>
    <div v-if="show && props.appointment" class="fixed inset-0 bg-neutral/80  flex items-center justify-center z-50" @click.self="emit('close')">

        <div class="glass-effect p-6 rounded-lg border border-primary/20 shadow-md shadow-primary w-full max-w-md">
            <h3 class="text-xl font-semibold text-primary mb-4">{{props.appointment.subject}}</h3>

            
         

                    <div>
                        <h4 for="start_time" class="block text-sm font-medium text-primary">Start Time</h4>
                   <p>{{ props.appointment.start_time }}</p>
                    </div>

                    <div>
                        <h4 for="end_time" class="block text-sm font-medium text-primary">End Time</h4>
                       <p>{{ props.appointment.end_time }}</p>
                    </div>
<div>
<h4 class="block text-sm font-medium text-primary mb-2">Guests</h4>
    <ul class="list-disc list-inside">
        <li v-for="guest in props.appointment.extendedProps.guests.split(',')" :key="guest">
            {{ guest.trim() }}
        </li>
    </ul>   
</div>
              
        </div>
    </div>
</template>

<script setup lang="ts">
    import type { Appointment } from '@/types';
    
    interface Props {
        show: boolean;
        appointment?: Appointment | null;
    }
    const props = defineProps<Props>();
    const emit = defineEmits<{
        (e: 'close'): void;
    }>();
</script>

<style scoped>

</style>