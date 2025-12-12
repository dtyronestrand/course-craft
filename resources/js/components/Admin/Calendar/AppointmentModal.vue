<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center bg-base-300/80"
        @click.self="emit('close')"
    >
        <div
            class="bg-base-100 w-full max-w-md rounded-lg border border-primary p-6 shadow-md shadow-primary"
        >
            <h3 class="mb-4 text-xl font-semibold text-primary">
                Schedule New Appointment
            </h3>
            <form @submit.prevent="submit">
           
                <div class="space-y-4">
                    <div>
                        <label
                            for="subject"
                            class="block text-sm font-medium text-primary"
                            >Subject</label
                        >
                        <input
                            id="subject"
                            type="text"
                            v-model="form.subject"
                            class=" bg-base-200 border border-primary mt-1 block w-full rounded-md  p-2 text-base-content "
                            required
                        />
                        <div
                            v-if="form.errors.subject"
                            class="mt-1 text-xs text-error"
                        >
                            {{ form.errors.subject }}
                        </div>
                    </div>

                    <div>
                        <label
                            for="start_time"
                            class="block text-sm font-medium text-primary"
                            >Start Time</label
                        >
                        <input
                            id="start_time"
                            type="datetime-local"
                            v-model="form.start_time"
                            class=" bg-base-200 border border-primary mt-1 block w-full rounded-md  p-2 text-base-content "
                            required
                        />
                        <div
                            v-if="form.errors.start_time"
                            class="mt-1 text-xs text-error"
                        >
                            {{ form.errors.start_time }}
                        </div>
                    </div>

                    <div>
                        <label
                            for="end_time"
                            class="block text-sm font-medium text-primary"
                            >End Time</label
                        >
                        <input
                            id="end_time"
                            type="datetime-local"
                            v-model="form.end_time"
                            class=" bg-base-200 border border-primary mt-1 block w-full rounded-md  p-2 text-base-content "
                            required
                        />
                        <div
                            v-if="form.errors.end_time"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.end_time }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-sm font-medium text-primary"
                            >Guests</label
                        >
                        <div
                            class="mb-2 flex flex-wrap gap-2"
                            v-if="selectedUsers.length"
                        >
                            <span
                                v-for="user in selectedUsers"
                                :key="user.id"
                                class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-sm text-base-content"
                            >
                                {{ user.first_name }} {{ user.last_name }}
                                <button
                                    type="button"
                                    @click="removeGuest(user.id)"
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
                                        'bg-primary': form.guests.includes(
                                            user.id,
                                        ),
                                    }"
                                >
                                    {{ user.first_name }} {{ user.last_name }}
                                </button>
                            </div>
                        </div>
                        <div
                            v-if="form.errors.guests"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.guests }}
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button
                        type="button"
                        @click="emit('close')"
                        class="rounded-md bg-error text-error-content px-4 py-2 hover:bg-error/30 active:bg-error/50"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-md bg-success px-4 py-2 text-success-content active:bg-success/50 hover:bg-success/30 disabled:bg-neutral/50 disabled:text-neutral/70"
                    >
                        {{
                            form.processing ? 'Saving...' : 'Create Appointment'
                        }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
const props = defineProps<{
    show: boolean;
    selectedDate: string;
    users: Array<any>;
}>();
const page = usePage();
const emit = defineEmits(['close']);


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
    return props.users.filter((user) =>
        `${user.first_name} ${user.last_name}`
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase()),
    );
});

const selectedUsers = computed(() =>
    props.users.filter((user) => form.guests.includes(user.id)),
);

const addGuest = (userId: number) => {
    if (!form.guests.includes(userId)) {
        form.guests.push(userId);
    }
    searchQuery.value = '';
};

const removeGuest = (userId: number) => {
    form.guests = form.guests.filter((id) => id !== userId);
};

watch(
    () => props.show,
    (isShown) => {
        if (isShown && props.selectedDate) {
            const now = new Date();
            const time = now.toLocaleTimeString('en-US', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
            });
            const plusHour = new Date(now.getTime() + 60 * 60 * 1000);
            const endTime = plusHour.toLocaleTimeString('en-US', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
            });
            form.start_time = `${props.selectedDate}T${time}`;
            form.end_time = `${props.selectedDate}T${endTime}`;
        }
    },
);

const submit = () => {
    form.post('/appointments', {
        onSuccess: () => {
            form.reset();
            emit('close');
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};
</script>

<style scoped></style>
