<template>
    <div
        v-if="show && props.appointment && !editAppointment"
        class="fixed inset-0 z-50 flex items-center justify-center bg-base-300/80"
        @click.self="emit('close')"
    >
        <div
            class="bg-base-100 w-full max-w-md rounded-lg border border-primary p-6 shadow-md shadow-primary"
        >
        <div class="flex flex-row justify-between">
            <h3 class="mb-4 text-xl font-semibold text-base-content">
                {{ props.appointment.subject }}
            </h3>
            <button
            class="btn btn-primary" @click="() => { localAppointment = JSON.parse(JSON.stringify(props.appointment)); editAppointment = true; }">Edit</button>
        </div>

            <div>
                <h4
                    for="start_time"
                    class="block text-sm font-medium text-base-content"
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
    <div
        v-if="show && props.appointment && editAppointment"
        class="fixed inset-0 z-50 flex items-center justify-center bg-base-300/80"
        @click.self="emit('close')"
    >
        <div
            class="bg-base-100 w-full max-w-md rounded-lg border border-primary p-6 shadow-md shadow-primary"
        >
    <input type="text" v-model="localAppointment!.subject"/>
    <div>
       <h4
                    for="start_time"
                    class="block text-sm font-medium text-base-content"
                >
                    Start Time
                </h4>
                <input type="datetime-local" v-model="editStartTime" @change="localAppointment!.start_time = editStartTime"/>
    </div>
    <div>
    <h4                   for="end_time"
                    class="block text-sm font-medium text-primary"
                >
                    End Time
                </h4>
                <input type="datetime-local" v-model="editEndTime" @change="localAppointment!.end_time = editEndTime"/>
    </div>
       <div
                            class="mb-2 flex flex-wrap gap-2"
                            v-if="editGuests.length"
                        >
                            <span
                                v-for="guest in editGuests"
                                :key="guest.id"
                                class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-sm text-base-content"
                            >
                                {{ guest.first_name }} {{ guest.last_name }}
                                <button
                                    type="button"
                                    @click="removeGuest(guest.id)"
                                    class="ml-2 text-base-content hover:text-error"
                                >
                                    &times;
                                </button>
                            </span>
                        </div>
                        <div class="relative">
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Search users..."
                             class=" bg-base-200 border border-primary mt-1 block w-full rounded-md  p-2 text-base-content "
                            />
                            <div
                                v-if="searchQuery && filteredUsers.length"
                                class="absolute z-10 mt-1 max-h-48 w-full overflow-y-auto rounded-md border border-primary bg-neutral shadow-lg"
                            >
                                <button
                                    v-for="user in filteredUsers"
                                    :key="user.id"
                                    type="button"
                                    @click="addGuest(user.id)"
                                    class="w-full px-4 py-2 text-left hover:bg-primary/10"
                                    :class="{
                                        'bg-primary': editGuests.some(g => g.id === user.id)
                                    }"
                                >
                                    {{ user.first_name }} {{ user.last_name }}
                                </button>
                                </div>
                                </div>
                                <div class="flex flex-row gap-4">
                            
                                <button class="btn btn-success text-success-content hover:bg-success/30 active:bg-success/50 mt-4" @click="editAppointment = false; emit('close')">Save</button>
                                <button class="btn btn-error text-error-content hover:bg-error/30 active:bg-error/50 mt-4" @click="updateAppointment">Cancel</button>
                                </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Appointment } from '@/types';
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
interface User {
    id: number;
    first_name: string;
    last_name: string;
}

interface Props {
    show: boolean;
    appointment?: Appointment | null;
    users?: User[];
}

const props = defineProps<Props>();
const editAppointment = ref(false);
const localAppointment = ref<Appointment | null>(null);
const emit = defineEmits<{
    (e: 'close'): void;
}>();
const searchQuery = ref('');
const editStartTime = ref('');
const editEndTime = ref('');

watch(() => props.show, (newVal) => {
    if (!newVal) {
        editAppointment.value = false;
    }
});

const formatForDatetimeLocal = (dateStr: string) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
};

watch(editAppointment, (isEdit) => {
    if (isEdit && localAppointment.value) {
        editStartTime.value = formatForDatetimeLocal(localAppointment.value.start_time);
        editEndTime.value = formatForDatetimeLocal(localAppointment.value.end_time);
    }
});

const editGuests = computed(() => {
    if (!localAppointment.value?.extendedProps?.guests) return [];
    const guests = localAppointment.value.extendedProps.guests;
    if (typeof guests === 'string') {
        const guestString = guests.trim();
        if (!guestString) return [];
        const guestNames = guestString.split(',').map(g => g.trim()).filter(g => g);
        return (props.users || []).filter(u => guestNames.includes(`${u.first_name} ${u.last_name}`));
    }
    return guests;
});
const filteredUsers = computed(() => {
    if (!searchQuery.value || !props.users) return props.users || [];
    return props.users.filter((user) =>
        `${user.first_name} ${user.last_name}`
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase()),
    );
});
const addGuest = (userId: number) => {
    const user = props.users?.find(u => u.id === userId);
    if (user && localAppointment.value?.extendedProps) {
        const guests: any = localAppointment.value.extendedProps.guests;
        if (typeof guests === 'string') {
            (localAppointment.value.extendedProps.guests as any) = [user];
        } else if (Array.isArray(guests) && !guests.some((g: any) => g.id === userId)) {
            guests.push(user);
        }
    }
    searchQuery.value = '';
};

const removeGuest = (userId: number) => {
    if (localAppointment.value?.extendedProps?.guests && Array.isArray(localAppointment.value.extendedProps.guests)) {
        (localAppointment.value.extendedProps.guests as any) = (localAppointment.value.extendedProps.guests as any).filter((g: any) => g.id !== userId);
    }
};

const updateAppointment = () => {
    if (!localAppointment.value) return;
    const form = useForm({
        subject: localAppointment.value.subject,
        start_time: localAppointment.value.start_time,
        end_time: localAppointment.value.end_time,
        guests: editGuests.value.map(g => g.id),
    });
    form.post(`/admin/appointments/${localAppointment.value.id}`, {
        onSuccess: () => {
            editAppointment.value = false;
            emit('close');
        },
        onError: () => {
            alert('Failed to update appointment.');
        },
    });
};
</script>

<style scoped></style>
