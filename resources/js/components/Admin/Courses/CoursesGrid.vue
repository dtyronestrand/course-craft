<template>
    <div
        v-for="course in props.courses"
        :key="course.id"
        class="rounded-xl border border-primary bg-base-100 p-4 shadow shadow-primary"
    >
        <div class="mb-4 flex h-auto flex-row items-center gap-4">
            <div
                class="flex size-24 flex-col items-center justify-center rounded-full border border-primary bg-primary text-primary-content"
            >
                <h2 class="text-xl font-bold">{{ course.prefix }}</h2>
                <p>{{ course.number }}</p>
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="text-xl font-bold">{{ course.title }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-2">
            <div
                v-for="role in ['Designer', 'SME', 'Builder', 'Manager']"
                :key="role"
            >
                <div class="font-semibold">{{ role }}</div>
                <div>{{ getUserByRole(course.users, role) }}</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
interface Props {
    courses: any[];
}
const props = defineProps<Props>();

const getUserByRole = (users: any[], role: string) => {
    const user = users.find((u) => u.pivot.role === role);
    return user ? `${user.first_name} ${user.last_name}` : 'Not Assigned';
};
</script>

<style scoped></style>
