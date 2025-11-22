<template>
<div class="text-3xl col-span-1 row-span-1 bg-neutral text-neutral-content rounded-xl p-4 mx-4">
<h2>{{props.title}}</h2>
<p>{{props.info}}</p>
</div>

</template>

<script setup lang="ts">
interface InfoCardProps {
    title: string;
    info: any;
}
const props = defineProps<InfoCardProps>();
</script>

<style scoped>

</style>