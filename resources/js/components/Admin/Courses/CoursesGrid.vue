<template>
    <div v-for="course in props.courses" :key="course.id" class="bg-base-100 rounded-xl border border-primary shadow shadow-primary p-4">
      
         <div class="flex flex-row gap-4 h-auto mb-4 items-center">
                <div
                    class="flex size-24 flex-col items-center justify-center rounded-full border border-primary bg-primary text-primary-content"
                >
                    <h2 class="text-xl font-bold">{{ course.prefix }}</h2>
                    <p>{{ course.number }}</p>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-xl font-bold">{{ course.title }}</h3>
                </div>
         </div>

                    <div class="grid grid-cols-4 gap-2">
                        <div v-for="role in ['Designer', 'SME', 'Builder', 'Manager']" :key="role">
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
    const user = users.find(u => u.pivot.role === role);
    return user ? `${user.first_name} ${user.last_name}` : 'Not Assigned';
};
</script>

<style scoped></style>
