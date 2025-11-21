<template>
    <div>
        <input type="text" placeholder="Search..." v-model="query" class="input input-bordered w-full max-w-xs mb-4"/>
        
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
const query = ref('');

</script>

<style scoped>

</style>