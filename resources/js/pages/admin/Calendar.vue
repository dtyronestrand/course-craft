<template>
 <AdminLayout>
    <div class="p-6  min-h-screen w-full">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-base-content">{{ monthName }}</h1>
            <div class="space-x-2">
                <button @click="changeMonth(-1)" class="px-4 py-2 bg-info/20 frosted-backdrop border border-info text-info rounded shadow hover:bg-info/10">
                    &larr; Prev
                </button>
                <button @click="changeMonth(1)" class="px-4 py-2 bg-info/20 frosteed-backdrop border border-info text-info rounded shadow hover:bg-info/10">
                    Next &rarr;
                </button>
            </div>
        </div>

        <div class="glass-effect rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-7 border-b border-primary glass-effect text-primary py-2 font-semibold ">
                <div class="pl-2" v-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day">
                    {{ day }}
                </div>
            </div>

            <div class="grid grid-cols-7 auto-rows-fr">

                <div 
                    v-for="n in paddingDays" 
                    :key="'pad-' + n" 
                    class="h-32 border-b border-r border-primary bg-neutral"
                ></div>

                <div 
                    v-for="day in daysInMonth" 
                    :key="'day-' + day" 
                    class="glass-effect h-32 border-b border-r border-primary p-2 text-base-content  transition relative group"
            
                >
                    <div class="font-bold mb-1">{{ day }}</div>

                    <div class="space-y-1 overflow-y-auto max-h-24">
                        <div 
                            v-for="(appointment , index) in getAppointmentsForDay(day)" 
                            :key="appointment.id"
                            class="text-xs p-1 rounded text-white truncate cursor-pointer"
                        @click="viewAppointmentDetails(appointment)"
                            :subject="appointment.subject"
                            :class="index%2===0 ? 'bg-accent hover:bg-primary hover:text-primary-content' : 'bg-secondary hover:bg-primary hover:text-primary-content'"
                        >
                            {{ appointment.subject }} - {{ appointment.start_time }}
                        </div>
                    </div>

                    <button class="absolute bottom-1 right-1 opacity-0 group-hover:opacity-100 bg-accent rounded-full w-6 h-6 flex items-center justify-center text-accent-content hover:bg-info hover:text-info-content" type="button"  @click="openModal(day)">
                        +
                    </button>
                </div>
                <AppointmentModal
                :show="showModal"
                :selectedDate="selectedDate || ''"
                :users="props.users"
                @close="showModal=false"
                />
                <ViewAppointmentDetails
                :show="viewAppointmentDetailsModal"
                :appointment="selectedAppointment"
                @close="viewAppointmentDetailsModal=false"
                />
            </div>
        </div>
    </div>
 </AdminLayout>
</template>

<script setup lang="ts">
import {computed, ref} from 'vue';
import AdminLayout from "../../layouts/AdminLayout.vue";
import AppointmentModal from "@/components/Admin/Calendar/AppointmentModal.vue"
import type { Appointment } from '@/types';
import ViewAppointmentDetails from '@/components/Admin/Calendar/AppointmentDetails.vue';
const props = defineProps<{
    appointments: Appointment[],
    users: Array<any>
}>();



const currentMonthStart = ref(new Date());

const getDaysInMonth = (date:any)=>{
    return new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
}
const getFirstDayOfMonth = (date:any)=>{
    return new Date(date.getFullYear(), date.getMonth(), 1).getDay();
}
const daysInMonth = computed(() => getDaysInMonth(currentMonthStart.value));
const paddingDays = computed(() => getFirstDayOfMonth(currentMonthStart.value));



const changeMonth = (offset: number) => {
    const date = new Date(currentMonthStart.value);
    date.setMonth(date.getMonth() + offset);
    currentMonthStart.value = date;
};

const getAppointmentsForDay = (dayNumber:any) => {
    const year = currentMonthStart.value.getFullYear();
    const month = String(currentMonthStart.value.getMonth() + 1).padStart(2, '0');
    const day = String(dayNumber).padStart(2, '0');
    const dateString = `${year}-${month}-${day}`;

    return props.appointments.filter(appointment => appointment.start_time.startsWith(dateString));
}

const monthName = computed(() => currentMonthStart.value.toLocaleString('default', { month: 'long', year: 'numeric' }));

const showModal = ref(false);
const selectedDate = ref<string | null>(null);

const openModal = (dayNumber:any) => {
    const year = currentMonthStart.value.getFullYear();
    const month = String(currentMonthStart.value.getMonth() + 1).padStart(2, '0');
    const day = String(dayNumber).padStart(2, '0');
    selectedDate.value = `${year}-${month}-${day}`;
    showModal.value = true;
}

const viewAppointmentDetailsModal = ref(false);
const selectedAppointment = ref<any>(null);
const viewAppointmentDetails = (appointment:Appointment) => {
    viewAppointmentDetailsModal.value = true;
    selectedAppointment.value = appointment;

}
</script>

<style scoped>

</style>