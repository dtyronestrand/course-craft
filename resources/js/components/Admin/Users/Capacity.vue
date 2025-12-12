<template>
    <div>
        <progess
            class="progress w-56"
            value="props.value*10"
            max="100"
        ></progess>
        <p>{{ `${Math.round((value / 10) * 100)}%` }}</p>
    </div>
</template>

<script setup lang="ts">
interface Props {
    value: number;
}
const props = defineProps<Props>();
</script>

<style scoped></style>
