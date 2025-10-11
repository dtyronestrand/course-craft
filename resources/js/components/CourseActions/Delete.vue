<template>
    <div class="flex flex-row gap-2 space-between">

   
    <button @click="emit('delete')" role="button"  class="btn btn-error">Delete</button>

    </div>
</template>

<script setup lang="ts">
const emit= defineEmits(['delete'])

</script>

<style scoped>

</style>