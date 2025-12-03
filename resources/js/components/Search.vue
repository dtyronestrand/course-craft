<template>
    <div>
        <input
            type="text"
            placeholder="Search..."
            v-model="query"
            class="input-bordered input mb-4 w-full max-w-xs"
        />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
const query = ref('');
</script>

<style scoped></style>
