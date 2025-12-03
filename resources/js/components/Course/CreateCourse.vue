<template>
    <div
        class="bg-opacity-50 pointer-events-none fixed inset-0 z-50 flex items-center justify-center bg-base-100/80"
        @click.self="$emit('close')"
    >
        <div
            class="glass-effect pointer-events-auto mx-4 w-full max-w-md rounded-lg border border-primary p-6"
        >
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-base-content">
                    Create Course
                </h2>
                <button
                    @click="$emit('close')"
                    class="text-primary-content hover:text-error"
                >
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="var(--color-error)"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>
            </div>
            <form @submit.prevent="$emit('create-course', courseData)">
                <div class="mb-4">
                    <label
                        for="prefix"
                        class="block text-sm font-medium text-base-content"
                        >Course Prefix</label
                    >
                    <input
                        v-model="courseData.prefix"
                        type="text"
                        id="prefix"
                        class="frosted-backdrop mt-1 block w-full border-b border-primary bg-primary/10 p-2 shadow-sm shadow-primary"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="number"
                        class="block text-sm font-medium text-base-content"
                        >Course Number</label
                    >
                    <input
                        v-model="courseData.number"
                        type="text"
                        id="number"
                        class="frosted-backdrop mt-1 block w-full border-b border-primary bg-primary/10 p-2 shadow-sm shadow-primary"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="title"
                        class="block text-sm font-medium text-base-content"
                        >Course Title</label
                    >
                    <input
                        v-model="courseData.title"
                        type="text"
                        id="title"
                        class="frosted-backdrop mt-1 block w-full border-b border-primary bg-primary/10 p-2 shadow-sm shadow-primary"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="cycle"
                        class="block text-sm font-medium text-base-content"
                        >Development Cycle</label
                    >
                    <select
                        v-model="courseData.development_cycle"
                        id="cycle"
                        class="frosted-backdrop mt-1 block w-full border-b border-primary bg-primary/10 p-2 shadow-sm shadow-primary"
                    >
                        <option :value="null">Select a cycle</option>
                        <option
                            v-for="cycle in developmentCycles"
                            :key="cycle.id"
                            :value="cycle.id"
                        >
                            {{ cycle.name }}
                        </option>
                    </select>
                </div>
                <div class="mb-4">
                    <label
                        for="objectives"
                        class="mb-4 block text-sm font-medium text-base-content"
                        >Course Objectives</label
                    >
                    <div
                        v-for="(objective, index) in courseData.objectives"
                        :key="index"
                        class="mb-2 flex items-center gap-2"
                    >
                        <p class="mb-0">{{ objective.number }}.</p>
                        <input
                            v-model="objective.objective"
                            type="text"
                            :id="'objective-' + index"
                            class="frosted-backdrop mt-1 block w-full border-b border-primary bg-primary/10 p-2 shadow-sm shadow-primary"
                        />
                        <button
                            type="button"
                            @click="courseData.objectives.splice(index, 1)"
                            class="frosted-backdrop min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border border-error bg-error/10 px-[1.5em] py-[0.25em] text-center font-bold text-error duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                        </button>
                    </div>
                    <button
                        type="button"
                        @click="createObjective"
                        class="frosted-backdrop min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 mt-4 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border border-info bg-info/10 px-[1.5em] py-[0.25em] text-center font-bold text-info duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                    >
                        Add Objective
                    </button>
                </div>

                <div class="mb-4">
                    <label
                        for="users"
                        class="mb-4 block text-sm font-medium text-base-content"
                    >
                        Users</label
                    >
                    <div
                        v-for="user in courseData.users"
                        :key="user.id"
                        class="text-base-content"
                    >
                        {{ user.first_name }} {{ user.last_name }}
                        <select
                            v-model="user.role"
                            class="frosted-backdrop ml-2 rounded-md border border-primary bg-primary/10 p-1 shadow-sm shadow-primary"
                        >
                            <option value="Designer">Designer</option>
                            <option value="SME">Subject Matter Expert</option>
                            <option value="Manager">Manager</option>
                            <option value="Builder">Builder</option>
                        </select>
                    </div>

                    <select
                        v-if="addingUser"
                        @change="updateUsers"
                        v-model="selectedUserId"
                        id="users"
                        class="mt-1 block w-full rounded-md border border-secondary p-2 shadow-sm"
                    >
                        <option
                            class="text-base-content"
                            v-for="user in users"
                            :key="user.id"
                            :value="user.id"
                        >
                            {{ user.first_name }} {{ user.last_name }}
                        </option>
                    </select>
                    <button
                        type="button"
                        @click="addUser"
                        class="frosted-backdrop min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 mt-4 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border border-info bg-info/10 px-[1.5em] py-[0.25em] text-center font-bold text-info duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                    >
                        Add User
                    </button>
                </div>
                <div class="flex gap-2">
                    <button
                        @click="handleCreateCourse"
                        class="frosted-backdrop min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border border-success bg-success/10 px-[1.5em] py-[0.25em] text-center font-bold text-success duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                    >
                        Create Course
                    </button>
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="frosted-backdrop min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block -translate-y-0.5 cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border border-error bg-error/10 px-[1.5em] py-[0.25em] text-center font-bold text-error duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

const emit = defineEmits(['create-course', 'close']);

interface User {
    id: number;
    first_name: string;
    last_name: string;
    role?: string;
}

interface Objective {
    number: string;
    objective: string;
}

interface CourseData {
    prefix: string;
    number: string;
    title: string;
    objectives: Objective[];
    users: User[];
    development_cycle: number | null;
}

interface DevelopmentCycle {
    id: number;
    name: string;
}

const handleCreateCourse = () => {
    if (
        courseData.value.prefix &&
        courseData.value.number &&
        courseData.value.title
    ) {
        // Emit the create-course event with the course data
        // Ensure objectives without text are filtered out
        const filteredObjectives = courseData.value.objectives.filter(
            (obj) => obj.objective.trim() !== '',
        );
        const courseToCreate = {
            ...courseData.value,
            objectives: filteredObjectives,
        };
        // Emit the event
        emit('create-course', courseToCreate);
        // Reset the form
        courseData.value = {
            prefix: '',
            number: '',
            title: '',
            objectives: [],
            users: [],
            development_cycle: null,
        };
    } else {
        alert('Please fill in all required fields.');
    }
};
const toRoman = (num: number): string => {
    if (typeof num !== 'number' || num <= 0 || !Number.isInteger(num)) {
        return '';
    }

    const lookup: Record<string, number> = {
        M: 1000,
        CM: 900,
        D: 500,
        CD: 400,
        C: 100,
        XC: 90,
        L: 50,
        XL: 40,
        X: 10,
        IX: 9,
        V: 5,
        IV: 4,
        I: 1,
    };

    let roman = '';
    for (const key in lookup) {
        while (num >= lookup[key]) {
            roman += key;
            num -= lookup[key];
        }
    }
    return roman;
};

const users = ref<User[]>([]);
const developmentCycles = ref<DevelopmentCycle[]>([]);
const courseData = ref<CourseData>({
    prefix: '',
    number: '',
    title: '',
    objectives: [],
    users: [],
    development_cycle: null,
});
const addingUser = ref(false);
const selectedUserId = ref<number | null>(null);

const addUser = () => {
    addingUser.value = true;
};

const updateUsers = () => {
    if (selectedUserId.value) {
        const selectedUser = users.value.find(
            (user) => user.id === selectedUserId.value,
        );
        if (
            selectedUser &&
            !courseData.value.users.some(
                (user) => user.id === selectedUserId.value,
            )
        ) {
            courseData.value.users.push({ ...selectedUser, role: 'Designer' });
        }
        addingUser.value = false;
        selectedUserId.value = null;
    }
};
const createObjective = () => {
    courseData.value.objectives.push({
        number: toRoman(courseData.value.objectives.length + 1),
        objective: '',
    });
};

onMounted(async () => {
    try {
        const response = await fetch('/courses/create', {
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        });
        const data = await response.json();
        users.value = data.users;
        developmentCycles.value = data.cycles || [];
    } catch (error) {
        console.error('Failed to fetch users:', error);
    }
});
</script>

<style scoped>
.pointer-events-none {
    pointer-events: none;
}
.pointer-events-auto {
    pointer-events: auto;
}
</style>
