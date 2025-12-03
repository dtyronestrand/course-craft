<template>
    <div v-for="course in props.courses" :key="course.id" class="w-full">
        <div class="rounded-xl border border-primary/50 glass-effect p-4">
        <div class="flex items-center gap-4">
        <div class="rounded-full size-24 flex flex-col bg-primary/10 justify-center border frosted-backdrop text-primary border-primary items-center"><h2 class="font-bold text-xl">{{ course.prefix }}</h2><p>{{ course.number }}</p></div>
        <div>
        <h3>{{ course.title }}</h3>
        <div class="flow-root mt-2">
        <ul class="-m-1 flex flex-wrap">
        <li v-for="user in course.users" :key="user.id" class="p-1 leading-none">
        <p class="text-xs font-medium text-base-content">{{ user.first_name }} {{ user.last_name  }}: {{ user.pivot.role }}</p>
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
    courses: any[]
}
const props = defineProps<Props>()

</script>

<style scoped>

</style>