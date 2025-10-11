<template>
    <div class="flex flex-row gap-2 space-between">
    <buutton @click="emit('view')" role="button" class="btn btn-neutral">
        View
    </buutton>
    </div>
</template>

<script setup lang="ts">
const emit= defineEmits(['view'])

</script>

<style scoped>

</style>