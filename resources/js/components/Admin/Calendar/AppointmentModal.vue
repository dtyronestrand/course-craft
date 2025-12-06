<template>
    <div v-if="show" class="fixed inset-0 bg-neutral/80  flex items-center justify-center z-50" @click.self="emit('close')">

        <div class="glass-effect p-6 rounded-lg border border-primary/20 shadow-md shadow-primary w-full max-w-md">
            <h3 class="text-xl font-semibold text-primary mb-4">Schedule New Appointment</h3>
            <form @submit.prevent="submit">
            {{ test}}
                <div class="space-y-4">
                    <div>
                        <label for="subject" class="block text-sm font-medium text-primary">Subject</label>
                        <input id="subject" type="text" v-model="form.subject" class="text-base-content mt-1 block w-full border border-primary  rounded-md shadow-sm shadow-primary bg-primary/10 frosted-backdrop p-2" required>
                        <div v-if="form.errors.subject" class="text-error text-xs mt-1">{{ form.errors.subject }}</div>
                    </div>

                    <div>
                        <label for="start_time" class="block text-sm font-medium text-primary">Start Time</label>
                        <input id="start_time" type="datetime-local" v-model="form.start_time" class="text-base-content mt-1 block w-full border border-primary  rounded-md shadow-sm shadow-primary bg-primary/10 frosted-backdrop p-2" required>
                        <div v-if="form.errors.start_time" class="text-error text-xs mt-1">{{ form.errors.start_time }}</div>
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-primary">End Time</label>
                        <input id="end_time" type="datetime-local" v-model="form.end_time" class="text-base-content mt-1 block w-full border border-primary  rounded-md shadow-sm shadow-primary bg-primary/10 frosted-backdrop p-2"  required>
                        <div v-if="form.errors.end_time" class="text-red-500 text-xs mt-1">{{ form.errors.end_time }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-primary mb-2">Guests</label>
                        <div class="flex flex-wrap gap-2 mb-2" v-if="selectedUsers.length">
                            <span v-for="user in selectedUsers" :key="user.id" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary/10 text-base-content">
                                {{ user.first_name }} {{ user.last_name }}
                                <button type="button" @click="removeGuest(user.id)" class="ml-2 text-base-content hover:text-error">&times;</button>
                            </span>
                        </div>
                        <div class="relative">
                            <input 
                                type="text" 
                                v-model="searchQuery" 
                                placeholder="Search users..." 
                                class="text-base-content mt-1 block w-full border border-primary  rounded-md shadow-sm shadow-primary bg-primary/10 frosted-backdrop p-2" 
                            >
                            <div v-if="searchQuery && filteredUsers.length" class="absolute z-10 w-full mt-1 bg-neutral border border-primary rounded-md shadow-lg max-h-48 overflow-y-auto">
                                <button 
                                    v-for="user in filteredUsers" 
                                    :key="user.id" 
                                    type="button"
                                    @click="addGuest(user.id)"
                                    class="w-full text-left px-4 py-2 hover:bg-primary/10"
                                    :class="{'bg-primary': form.guests.includes(user.id)}"
                                >
                                    {{ user.first_name }} {{ user.last_name }}
                                </button>
                            </div>
                        </div>
                        <div v-if="form.errors.guests" class="text-red-500 text-xs mt-1">{{ form.errors.guests }}</div>
                    </div>

                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="emit('close')" class="px-4 py-2 text-error bg-error/10 frosted-backdrop border border-error shadow-sm shadow-error rounded-md hover:bg-error/30">
                        Cancel
                    </button>
                    <button type="submit" :disabled="form.processing" class="px-4 py-2 text-success bg-success/10 frosted-backdrop border border-success shadow-sm shadow-success rounded-md hover:bg-success/30 disabled:bg-neutral/50 disabled:text-neutral/70">
                        {{ form.processing ? 'Saving...' : 'Create Appointment' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import {useForm} from "@inertiajs/vue3";
import {watch, ref, computed} from "vue";
import {usePage} from "@inertiajs/vue3";
const props = defineProps<{
   show: boolean,
   selectedDate: string,
    users: Array<any>
}>();
const page = usePage();
const emit = defineEmits(['close']);
const test = new Date().getTime().toString().slice(0, 16);

const form = useForm({
    user: page.props.auth.user.id,
    subject: '',
    start_time: '',
    end_time: '',
    notes: '',
    guests: [] as Array<any>,
});

const searchQuery = ref('');
const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users;
    return props.users.filter(user => 
        `${user.first_name} ${user.last_name}`.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const selectedUsers = computed(() => 
    props.users.filter(user => form.guests.includes(user.id))
);

const addGuest = (userId: number) => {
    if (!form.guests.includes(userId)) {
        form.guests.push(userId);
    }
    searchQuery.value = '';
};

const removeGuest = (userId: number) => {
    form.guests = form.guests.filter(id => id !== userId);
};

watch(() => props.show, (isShown) => {
    if (isShown && props.selectedDate) {
        const now = new Date();
        const time = now.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' });
        const plusHour = new Date(now.getTime() + 60 * 60 * 1000);
        const endTime = plusHour.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' });
        form.start_time = `${props.selectedDate}T${time}`;
        form.end_time = `${props.selectedDate}T${endTime}`;
    }
})

const submit = () => {
    form.post('/appointments', {
        onSuccess: () => {
            form.reset();
            emit('close');
        },
        onError: (errors) => {
            console.log(errors);
        }
    });
}
</script>

<style scoped>

</style>