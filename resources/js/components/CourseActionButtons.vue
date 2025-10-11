<template>
    <div class="flex flex-row gap-2 space-between">
    <Link role="button" :href="`/courses/${props.id}/`" class="btn btn-neutral">
        View
    </Link>
    <Link role="button" :href="`/courses/${props.id}/map/`" class="btn btn-info">Course  Map</Link>
    <Link role="button" :href="`/courses/${props.id}/storyboard/`" class="btn btn-primary">Course Storyboard</Link>
   
    <Link role="button" :href="`/courses/${props.id}/delete/`"  class="btn btn-error">Delete</Link>

    </div>
</template>

<script setup lang="ts">
import {Link } from '@inertiajs/vue3'
const props = defineProps<{
    id: number
}>()

</script>

<style scoped>

</style>