<template>
    <div class="space-between flex flex-row gap-2">
        <Link
            role="button"
            :href="`/courses/${props.id}/`"
            class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block translate-y-[-2px] cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-info px-[1.5em] py-[0.25em] text-center font-bold text-info-content duration-300 will-change-transform outline-none hover:bg-neutral hover:text-neutral-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
        >
            View
        </Link>
        <Link
            role="button"
            :href="`/courses/${props.id}/map/`"
            class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block translate-y-[-2px] cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-primary px-[1.5em] py-[0.25em] text-center font-bold text-primary-content duration-300 will-change-transform outline-none hover:bg-neutral hover:text-neutral-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
            >Course Map</Link
        >
        <Link
            role="button"
            :href="`/courses/${props.id}/storyboard/`"
            class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block translate-y-[-2px] cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-secondary px-[1.5em] py-[0.25em] text-center font-bold text-secondary-content duration-300 will-change-transform outline-none hover:bg-neutral hover:text-neutral-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
            >Course Storyboard</Link
        >

        <Link
            role="button"
            :href="`/courses/${props.id}/delete/`"
            class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block translate-y-[-2px] cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-error px-[1.5em] py-[0.25em] text-center font-bold text-error-content duration-300 will-change-transform outline-none hover:bg-neutral hover:text-neutral-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
            >Delete</Link
        >
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
const props = defineProps<{
    id: number;
}>();
</script>

<style scoped></style>
