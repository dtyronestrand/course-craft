<template>
    <div class="flex flex-row gap-2 space-between">

    <button @click="emit('changeDisplay','map')" role="button" class="btn btn-info">Course  Map</button>

    </div>
</template>

<script setup lang="ts">
const emit = defineEmits(['changeDisplay'])
</script>

<style scoped>

</style>