<template>
    <div class="space-between flex flex-row gap-2">
        <buutton @click="emit('view')" role="button" class="btn btn-neutral">
            View
        </buutton>
    </div>
</template>

<script setup lang="ts">
const emit = defineEmits(['view']);
</script>

<style scoped></style>
