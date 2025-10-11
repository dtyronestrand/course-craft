<template>
    <div class="flex flex-row gap-2 space-between">

    <button @click="emit('changeDisplay', 'storyboard')" role="button" class="btn btn-secondary">Course Storyboard</button>
   

    </div>
</template>

<script setup lang="ts">
const emit = defineEmits(['changeDisplay'])

</script>

<style scoped>

</style>