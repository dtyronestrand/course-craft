<template>
    <div class="flex flex-row gap-2 space-between">

   
    <button @click="emit('delete')" role="button"  class="bg-error text-error-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] translate-y-[-2px] active:shadow-none active:translate-y-0" >Delete</button>

    </div>
</template>

<script setup lang="ts">
const emit= defineEmits(['delete'])

</script>

<style scoped>

</style>