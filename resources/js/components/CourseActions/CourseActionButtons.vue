<template>
    <div class="flex flex-row gap-2 space-between">
    <Link role="button" :href="`/courses/${props.id}/`" class="bg-info text-info-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-neutral-content hover:bg-neutral hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] translate-y-[-2px] active:shadow-none active:translate-y-0" >
        View
    </Link>
    <Link role="button" :href="`/courses/${props.id}/map/`" class="bg-primary text-primary-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-neutral-content hover:bg-neutral hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] translate-y-[-2px] active:shadow-none active:translate-y-0" >Course  Map</Link>
    <Link role="button" :href="`/courses/${props.id}/storyboard/`" class="bg-secondary text-secondary-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-neutral-content hover:bg-neutral hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] translate-y-[-2px] active:shadow-none active:translate-y-0" >Course Storyboard</Link>
   
    <Link role="button" :href="`/courses/${props.id}/delete/`"  class="bg-error text-error-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-neutral-content hover:bg-neutral hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] translate-y-[-2px] active:shadow-none active:translate-y-0" >Delete</Link>

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