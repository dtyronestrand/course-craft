<template>
    <div v-for="course in props.courses" :key="course.id" class="w-full">
        <div class="glass-effect rounded-xl border border-primary/50 p-4">
            <div class="flex items-center gap-4">
                <div
                    class="frosted-backdrop flex size-24 flex-col items-center justify-center rounded-full border border-primary bg-primary/10 text-primary"
                >
                    <h2 class="text-xl font-bold">{{ course.prefix }}</h2>
                    <p>{{ course.number }}</p>
                </div>
                <div>
                    <h3>{{ course.title }}</h3>
                    <div class="mt-2 flow-root">
                        <ul class="-m-1 flex flex-wrap">
                            <li
                                v-for="user in course.users"
                                :key="user.id"
                                class="p-1 leading-none"
                            >
                                <p
                                    class="text-xs font-medium text-base-content"
                                >
                                    {{ user.first_name }} {{ user.last_name }}:
                                    {{ user.pivot.role }}
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
interface Props {
    courses: any[];
}
const props = defineProps<Props>();
</script>

<style scoped></style>
