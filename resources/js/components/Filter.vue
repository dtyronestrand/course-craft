<script lang="ts" setup>
import type { Column, Table } from '@tanstack/vue-table';
import { ListFilter } from 'lucide-vue-next';
import { computed, PropType, ref } from 'vue';
import DebouncedInput from './DebouncedInput.vue';
const props = defineProps({
    column: {
        type: Object as PropType<Column<any, unknown>>,
        required: true,
    },
    table: {
        type: Object as PropType<Table<any>>,
        required: true,
    },
});
const firstValue = computed(() =>
    props.table.getPreFilteredRowModel().flatRows[0]?.getValue(props.column.id),
);
const columnFilterValue = computed(() => props.column.getFilterValue());
const sortedUniqueValues = computed(() =>
    typeof firstValue.value === 'number'
        ? []
        : Array.from(props.column.getFacetedUniqueValues().keys()).sort(),
);

const dialogRef = ref<HTMLDialogElement>();
</script>

<template>
    <div v-if="typeof firstValue === 'number'">
        <div class="flex space-x-2">
            <DebouncedInput
                type="number"
                :min="Number(column.getFacetedMinMaxValues()?.[0] ?? '')"
                :max="Number(column.getFacetedMinMaxValues()?.[1] ?? '')"
                :modelValue="(columnFilterValue as [number, number])?.[0] ?? ''"
                @update:modelValue="
                    (value: number) =>
                        column.setFilterValue((old: [number, number]) => [
                            value,
                            old?.[1],
                        ])
                "
                :placeholder="`Min ${
                    column.getFacetedMinMaxValues()?.[0]
                        ? `(${column.getFacetedMinMaxValues()?.[0]})`
                        : ''
                }`"
                class="w-24 rounded border shadow"
            />
            <DebouncedInput
                type="number"
                :min="Number(column.getFacetedMinMaxValues()?.[0] ?? '')"
                :max="Number(column.getFacetedMinMaxValues()?.[1] ?? '')"
                :modelValue="(columnFilterValue as [number, number])?.[1] ?? ''"
                @update:modelValue="
                    (value: number) =>
                        column.setFilterValue((old: [number, number]) => [
                            old?.[0],
                            value,
                        ])
                "
                :placeholder="`Max ${
                    column.getFacetedMinMaxValues()?.[1]
                        ? `(${column.getFacetedMinMaxValues()?.[1]})`
                        : ''
                }`"
                class="w-24 rounded border shadow"
            />
        </div>
        <div class="h-1" />
    </div>
    <div v-else>
        <button
            @click="dialogRef?.showModal()"
            class="btn border-none bg-transparent shadow-none hover:bg-neutral"
        >
            <ListFilter class="h-4 text-primary" />
        </button>
        <dialog ref="dialogRef" :id="`filter-${column.id}`" class="modal">
            <div class="modal-box">
                <select
                    class="w-full"
                    :value="(columnFilterValue ?? '') as string"
                    @change="
                        column.setFilterValue(
                            ($event.target as HTMLSelectElement).value,
                        )
                    "
                >
                    <option value="">All</option>
                    <option
                        v-for="value in sortedUniqueValues.slice(0, 5000)"
                        :key="value"
                        :value="value"
                    >
                        {{ value }}
                    </option>
                </select>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>
</template>
