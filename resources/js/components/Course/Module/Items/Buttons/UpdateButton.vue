<template>
    <button
        type="submit"
        class="min-height-[3.75em] min-width-0 decoration-none transition-[all cubic-bezier(.23,1, 0.32,1)] user-select-none hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] m-0 box-border inline-block translate-y-[-2px] cursor-pointer touch-manipulation appearance-none rounded-[0.9375em] border-[0.125em] border-secondary bg-success px-[1.5em] py-[0.25em] text-center font-bold text-success-content duration-300 will-change-transform outline-none hover:bg-secondary hover:text-secondary-content active:translate-y-0 active:shadow-none disabled:pointer-events-none"
    >
        Update {{ capitalize(props.itemType) }}
    </button>
</template>

<script setup lang="ts">
interface Props {
    itemType: string;
}
const props = defineProps<Props>();

const capitalize = (s: string) => {
    if (s === 'wrap_up') {
        return 'Wrap Up';
    }
    return s.charAt(0).toUpperCase() + s.slice(1);
};
</script>

<style scoped></style>
