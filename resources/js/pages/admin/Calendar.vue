<template>
    <AdminLayout>
        <div class="min-h-screen w-full p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-base-content">
                    {{ monthName }}
                </h1>
                <div class="space-x-2">
                    <button
                        @click="changeMonth(-1)"
                        class="rounded border border-primary bg-primary px-4 py-2 text-primary-content hover:bg-primary/30 active:bg-primary/50"
                    >
                        &larr; Prev
                    </button>
                    <button
                        @click="changeMonth(1)"
                        class="rounded border border-primary bg-primary px-4 py-2 text-primary-content hover:bg-primary/30 active:bg-primary/50"
                    >
                        Next &rarr;
                    </button>
                </div>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-primary bg-primary shadow"
            >
                <div
                    class="grid grid-cols-7 border border-primary bg-primary py-2 font-semibold text-primary-content"
                >
                    <div
                        class="pl-2"
                        v-for="day in [
                            'Sun',
                            'Mon',
                            'Tue',
                            'Wed',
                            'Thu',
                            'Fri',
                            'Sat',
                        ]"
                        :key="day"
                    >
                        {{ day }}
                    </div>
                </div>

                <div class="grid auto-rows-fr grid-cols-7">
                    <div
                        v-for="n in paddingDays"
                        :key="'pad-' + n"
                        class="h-32 border-r border-b border-primary bg-primary"
                    ></div>

                    <div
                        v-for="day in daysInMonth"
                        :key="'day-' + day"
                        class="group relative h-32 border-r border-b border-primary bg-base-100 p-2 text-base-content transition"
                    >
                        <div class="mb-1 font-bold">{{ day }}</div>

                        <div class="max-h-16 space-y-1 overflow-y-auto">
                            <div
                                v-for="(
                                    appointment, index
                                ) in getAppointmentsForDay(day)"
                                :key="appointment.id"
                                class="cursor-pointer  rounded p-1 text-xs"
                                @click="viewAppointmentDetails(appointment)"
                                :subject="appointment.subject"
                                :class="
                                    index % 2 === 0
                                        ? 'text-acceent-content bg-accent hover:bg-primary hover:text-primary-content'
                                        : 'bg-secondary text-secondary-content hover:bg-primary hover:text-primary-content'
                                "
                            >
                                {{ appointment.subject }} -
                                {{ dayjs(appointment.start_time).format('hh:mma') }}
                            </div>
                        </div>

                        <button
                            class="absolute right-1 bottom-1 flex h-6 w-6 items-center justify-center rounded-full bg-accent text-accent-content opacity-0 group-hover:opacity-100 hover:bg-info hover:text-info-content"
                            type="button"
                            @click="openModal(day)"
                        >
                            +
                        </button>
                    </div>
                    <AppointmentModal
                        :show="showModal"
                        :selectedDate="selectedDate || ''"
                        :users="props.users"
                        @close="showModal = false"
                    />
                    <ViewAppointmentDetails
                        :show="viewAppointmentDetailsModal"
                        :appointment="selectedAppointment"
                        :users="props.users"
                        @close="viewAppointmentDetailsModal = false"
                    />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import ViewAppointmentDetails from '@/components/Admin/Calendar/AppointmentDetails.vue';
import AppointmentModal from '@/components/Admin/Calendar/AppointmentModal.vue';
import type { Appointment } from '@/types';
import { computed, ref } from 'vue';
import AdminLayout from '../../layouts/AdminLayout.vue';
import dayjs from 'dayjs';
const props = defineProps<{
    appointments: Appointment[];
    users: Array<any>;
}>();

const currentMonthStart = ref(new Date());

const getDaysInMonth = (date: any) => {
    return new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
};
const getFirstDayOfMonth = (date: any) => {
    return new Date(date.getFullYear(), date.getMonth(), 1).getDay();
};
const daysInMonth = computed(() => getDaysInMonth(currentMonthStart.value));
const paddingDays = computed(() => getFirstDayOfMonth(currentMonthStart.value));

const changeMonth = (offset: number) => {
    const date = new Date(currentMonthStart.value);
    date.setMonth(date.getMonth() + offset);
    currentMonthStart.value = date;
};

const getAppointmentsForDay = (dayNumber: any) => {
    const year = currentMonthStart.value.getFullYear();
    const month = String(currentMonthStart.value.getMonth() + 1).padStart(
        2,
        '0',
    );
    const day = String(dayNumber).padStart(2, '0');
    const dateString = `${year}-${month}-${day}`;

    return props.appointments.filter((appointment) =>
        appointment.start_time.startsWith(dateString),
    );
};

const monthName = computed(() =>
    currentMonthStart.value.toLocaleString('default', {
        month: 'long',
        year: 'numeric',
    }),
);

const showModal = ref(false);
const selectedDate = ref<string | null>(null);

const openModal = (dayNumber: any) => {
    const year = currentMonthStart.value.getFullYear();
    const month = String(currentMonthStart.value.getMonth() + 1).padStart(
        2,
        '0',
    );
    const day = String(dayNumber).padStart(2, '0');
    selectedDate.value = `${year}-${month}-${day}`;
    showModal.value = true;
};

const viewAppointmentDetailsModal = ref(false);
const selectedAppointment = ref<any>(null);
const viewAppointmentDetails = (appointment: Appointment) => {
    viewAppointmentDetailsModal.value = true;
    selectedAppointment.value = appointment;
};
</script>

<style scoped></style>
