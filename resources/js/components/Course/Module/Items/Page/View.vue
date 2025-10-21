<template>
    <div class="border rounded-lg p-4 mb-4">
      
    </div>
</template>

<script setup lang="ts">
import { CourseModule } from '@/types';

interface Props {
    modules: CourseModule[];
}
const props = defineProps<Props>();
</script>

<style scoped>

</style>