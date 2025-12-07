<template>
    <div
        v-if="show && props.appointment"
        class="fixed inset-0 z-50 flex items-center justify-center bg-neutral/80"
        @click.self="emit('close')"
    >
        <div
            class="glass-effect w-full max-w-md rounded-lg border border-primary/20 p-6 shadow-md shadow-primary"
        >
            <h3 class="mb-4 text-xl font-semibold text-primary">
                {{ props.appointment.subject }}
            </h3>

            <div>
                <h4
                    for="start_time"
                    class="block text-sm font-medium text-primary"
                >
                    Start Time
                </h4>
                <p>{{ props.appointment.start_time }}</p>
            </div>

            <div>
                <h4
                    for="end_time"
                    class="block text-sm font-medium text-primary"
                >
                    End Time
                </h4>
                <p>{{ props.appointment.end_time }}</p>
            </div>
            <div>
                <h4 class="mb-2 block text-sm font-medium text-primary">
                    Guests
                </h4>
                <ul class="list-inside list-disc">
                    <li
                        v-for="guest in props.appointment.extendedProps.guests.split(
                            ',',
                        )"
                        :key="guest"
                    >
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

<style scoped></style>
